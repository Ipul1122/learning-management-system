<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreClassRequest;
use App\Http\Requests\AdminCabang\UpdateClassRequest;
use App\Models\ActivityLog;
use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    /**
     * Tampilkan seluruh kelas pelatihan yang diselenggarakan cabang ini.
     */
    public function index(Request $request): View
    {
        $admin = $request->user();
        $branch = $admin->branch;
        abort_unless($branch, 403, 'Anda belum terikat pada kantor cabang manapun.');

        $query = TrainingClass::query()
            ->where('branch_id', $branch->id)
            ->with(['trainer', 'sessions'])
            ->withCount('sessions');

        // Pencarian judul kelas atau nama trainer
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('trainer', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter tipe kelas (offline, online, hybrid)
        if ($request->filled('type') && in_array($request->input('type'), ['offline', 'online', 'hybrid'])) {
            $query->where('type', $request->input('type'));
        }

        // Filter status kelas
        if ($request->filled('status') && in_array($request->input('status'), ['draft', 'open', 'ongoing', 'completed', 'cancelled'])) {
            $query->where('status', $request->input('status'));
        }

        $classes = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => TrainingClass::where('branch_id', $branch->id)->count(),
            'open' => TrainingClass::where('branch_id', $branch->id)->where('status', 'open')->count(),
            'ongoing' => TrainingClass::where('branch_id', $branch->id)->where('status', 'ongoing')->count(),
            'completed' => TrainingClass::where('branch_id', $branch->id)->where('status', 'completed')->count(),
        ];

        return view('admin-cabang.classes.index', compact('classes', 'stats', 'branch'));
    }

    /**
     * Tampilkan formulir pembuatan kelas baru.
     */
    public function create(Request $request): View
    {
        $branch = $request->user()->branch;
        abort_unless($branch, 403, 'Anda belum terikat pada kantor cabang.');

        $trainers = User::trainers()
            ->where('branch_id', $branch->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin-cabang.classes.create', compact('branch', 'trainers'));
    }

    /**
     * Simpan kelas pelatihan baru ke database.
     */
    public function store(StoreClassRequest $request): RedirectResponse
    {
        $branch = $request->user()->branch;
        abort_unless($branch, 403, 'Anda belum terikat pada kantor cabang.');

        $data = $request->validated();
        $data['branch_id'] = $branch->id;
        $data['slug'] = TrainingClass::generateUniqueSlug($data['title']);

        $class = TrainingClass::create($data);

        ActivityLog::record(
            action: 'CREATE',
            description: "Admin Cabang membuat kelas baru: {$class->title} (Tipe: ".strtoupper($class->type).')',
            target: $class,
            old: null,
            new: $class->toArray(),
            branchId: $branch->id
        );

        return redirect()
            ->route('cabang.classes.show', $class)
            ->with('success', "Kelas {$class->title} berhasil dibuat! Silakan tambahkan jadwal sesi pembelajaran dan tautan Zoom.");
    }

    /**
     * Tampilkan rincian kelas, pemenuhan 20 JP, dan sesi Zoom.
     */
    public function show(Request $request, TrainingClass $class): View
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);

        $class->load(['trainer', 'sessions.creator']);

        return view('admin-cabang.classes.show', compact('class', 'branch'));
    }

    /**
     * Tampilkan formulir edit kelas.
     */
    public function edit(Request $request, TrainingClass $class): View
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);

        $trainers = User::trainers()
            ->where('branch_id', $branch->id)
            ->orderBy('name')
            ->get();

        return view('admin-cabang.classes.edit', compact('class', 'branch', 'trainers'));
    }

    /**
     * Perbarui data kelas.
     */
    public function update(UpdateClassRequest $request, TrainingClass $class): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);

        $oldData = $class->toArray();
        $data = $request->validated();

        if ($data['title'] !== $class->title) {
            $data['slug'] = TrainingClass::generateUniqueSlug($data['title'], $class->id);
        }

        $class->update($data);
        $newData = $class->fresh()->toArray();

        ActivityLog::record(
            action: 'UPDATE',
            description: "Memperbarui data kelas: {$class->title}",
            target: $class,
            old: $oldData,
            new: $newData,
            branchId: $branch->id
        );

        return redirect()
            ->route('cabang.classes.show', $class)
            ->with('success', "Data kelas {$class->title} berhasil diperbarui!");
    }

    /**
     * Ubah status operasional kelas secara cepat.
     */
    public function updateStatus(Request $request, TrainingClass $class): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);

        $request->validate([
            'status' => ['required', 'in:draft,open,ongoing,completed,cancelled'],
        ]);

        $oldStatus = $class->status;
        $newStatus = $request->input('status');

        $class->status = $newStatus;
        $class->save();

        ActivityLog::record(
            action: 'UPDATE',
            description: "Mengubah status kelas {$class->title} dari ".strtoupper($oldStatus).' menjadi '.strtoupper($newStatus),
            target: $class,
            old: ['status' => $oldStatus],
            new: ['status' => $newStatus],
            branchId: $branch->id
        );

        return redirect()
            ->back()
            ->with('success', 'Status kelas berhasil diubah menjadi '.strtoupper($newStatus).'!');
    }

    /**
     * Hapus kelas pelatihan.
     */
    public function destroy(Request $request, TrainingClass $class): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);

        $className = $class->title;
        $oldData = $class->toArray();
        $class->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "Menghapus kelas: {$className}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $branch->id
        );

        return redirect()
            ->route('cabang.classes.index')
            ->with('success', "Kelas {$className} berhasil dihapus!");
    }

    /**
     * Helper proteksi akses multi-cabang.
     */
    protected function authorizeClassAccess(TrainingClass $class, ?int $branchId): void
    {
        abort_unless(
            $branchId && $class->branch_id === $branchId,
            403,
            'Anda tidak memiliki wewenang mengakses kelas dari cabang lain.'
        );
    }
}
