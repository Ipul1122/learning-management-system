<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreTrainerRequest;
use App\Http\Requests\AdminCabang\UpdateTrainerRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TrainerController extends Controller
{
    /**
     * Tampilkan daftar trainer pengajar pada cabang admin login.
     */
    public function index(Request $request): View
    {
        $admin = $request->user();
        $branch = $admin->branch;
        abort_unless($branch, 403, 'Anda belum ditugaskan pada kantor cabang manapun.');

        $query = User::trainers()
            ->where('branch_id', $branch->id)
            ->withCount('assignedClasses');

        // Pencarian nama, email, nomor telepon
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status') && in_array($request->input('status'), ['active', 'inactive'])) {
            $query->where('status', $request->input('status'));
        }

        $trainers = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => User::trainers()->where('branch_id', $branch->id)->count(),
            'active' => User::trainers()->where('branch_id', $branch->id)->where('status', 'active')->count(),
            'inactive' => User::trainers()->where('branch_id', $branch->id)->where('status', 'inactive')->count(),
        ];

        return view('admin-cabang.trainers.index', compact('trainers', 'stats', 'branch'));
    }

    /**
     * Tampilkan form registrasi trainer baru untuk cabang ini.
     */
    public function create(Request $request): View
    {
        $branch = $request->user()->branch;
        abort_unless($branch, 403, 'Anda belum ditugaskan pada kantor cabang.');

        return view('admin-cabang.trainers.create', compact('branch'));
    }

    /**
     * Simpan trainer baru di cabang ini.
     */
    public function store(StoreTrainerRequest $request): RedirectResponse
    {
        $admin = $request->user();
        $branch = $admin->branch;
        abort_unless($branch, 403, 'Anda belum ditugaskan pada kantor cabang.');

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['branch_id'] = $branch->id;

        $trainer = User::create($data);
        $trainer->assignRole('trainer');

        ActivityLog::record(
            action: 'CREATE',
            description: "Admin Cabang mendaftarkan trainer baru: {$trainer->name} ({$trainer->email})",
            target: $trainer,
            old: null,
            new: $trainer->only(['id', 'name', 'email', 'branch_id', 'status']),
            branchId: $branch->id
        );

        return redirect()
            ->route('cabang.trainers.index')
            ->with('success', "Instruktur {$trainer->name} berhasil didaftarkan di cabang {$branch->name}!");
    }

    /**
     * Tampilkan formulir edit trainer.
     */
    public function edit(Request $request, User $trainer): View
    {
        $branch = $request->user()->branch;
        $this->authorizeTrainerAccess($trainer, $branch?->id);

        return view('admin-cabang.trainers.edit', compact('trainer', 'branch'));
    }

    /**
     * Perbarui data trainer.
     */
    public function update(UpdateTrainerRequest $request, User $trainer): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeTrainerAccess($trainer, $branch?->id);

        $oldData = $trainer->only(['name', 'email', 'phone_number', 'status']);
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $trainer->update($data);
        $newData = $trainer->fresh()->only(['name', 'email', 'phone_number', 'status']);

        ActivityLog::record(
            action: 'UPDATE',
            description: "Memperbarui profil trainer: {$trainer->name}",
            target: $trainer,
            old: $oldData,
            new: $newData,
            branchId: $branch->id
        );

        return redirect()
            ->route('cabang.trainers.index')
            ->with('success', "Data instruktur {$trainer->name} berhasil diperbarui!");
    }

    /**
     * Ubah status aktif/nonaktif trainer secara cepat.
     */
    public function toggleStatus(Request $request, User $trainer): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeTrainerAccess($trainer, $branch?->id);

        $oldStatus = $trainer->status;
        $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';

        $trainer->status = $newStatus;
        $trainer->save();

        $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            action: 'UPDATE',
            description: "Mengubah status trainer {$trainer->name} menjadi {$statusText}",
            target: $trainer,
            old: ['status' => $oldStatus],
            new: ['status' => $newStatus],
            branchId: $branch->id
        );

        return redirect()
            ->back()
            ->with('success', "Status trainer {$trainer->name} berhasil {$statusText}!");
    }

    /**
     * Hapus data trainer cabang.
     */
    public function destroy(Request $request, User $trainer): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeTrainerAccess($trainer, $branch?->id);

        if ($trainer->assignedClasses()->exists()) {
            return redirect()
                ->back()
                ->with('error', "Tidak dapat menghapus trainer {$trainer->name} karena masih memiliki kelas yang diampu. Silakan alihkan kelas atau nonaktifkan akun sebagai gantinya.");
        }

        $trainerName = $trainer->name;
        $oldData = $trainer->only(['id', 'name', 'email', 'branch_id']);
        $trainer->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "Menghapus akun trainer: {$trainerName}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $branch->id
        );

        return redirect()
            ->route('cabang.trainers.index')
            ->with('success', "Akun trainer {$trainerName} berhasil dihapus!");
    }

    /**
     * Helper proteksi akses: memastikan trainer adalah role trainer dan berada di cabang yang sama.
     */
    protected function authorizeTrainerAccess(User $trainer, ?int $branchId): void
    {
        abort_unless(
            $trainer->hasRole('trainer') && $branchId && $trainer->branch_id === $branchId,
            403,
            'Anda tidak memiliki wewenang mengelola trainer dari cabang lain.'
        );
    }
}
