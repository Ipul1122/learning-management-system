<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreBranchRequest;
use App\Http\Requests\SuperAdmin\UpdateBranchRequest;
use App\Models\ActivityLog;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    /**
     * Tampilkan daftar seluruh cabang dengan fitur filter dan pencarian.
     */
    public function index(Request $request): View
    {
        $query = Branch::query()->withCount('users');

        // Pencarian berdasarkan nama, kode, atau kota
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter status keaktifan
        if ($request->filled('status') && in_array($request->input('status'), ['active', 'inactive'])) {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        $branches = $query->latest()->paginate(10)->withQueryString();

        // Statistik ringkas untuk metric cards
        $stats = [
            'total' => Branch::count(),
            'active' => Branch::where('is_active', true)->count(),
            'inactive' => Branch::where('is_active', false)->count(),
        ];

        return view('super-admin.branches.index', compact('branches', 'stats'));
    }

    /**
     * Tampilkan formulir pembuatan cabang baru.
     */
    public function create(): View
    {
        return view('super-admin.branches.create');
    }

    /**
     * Simpan cabang baru ke database dan catat audit log.
     */
    public function store(StoreBranchRequest $request): RedirectResponse
    {
        $branch = Branch::create($request->validated());

        ActivityLog::record(
            action: 'CREATE',
            description: "Membuat cabang baru: {$branch->name} ({$branch->code})",
            target: $branch,
            old: null,
            new: $branch->toArray(),
            branchId: $branch->id
        );

        return redirect()
            ->route('admin.branches.index')
            ->with('success', "Cabang {$branch->name} berhasil ditambahkan!");
    }

    /**
     * Tampilkan formulir edit cabang.
     */
    public function edit(Branch $branch): View
    {
        return view('super-admin.branches.edit', compact('branch'));
    }

    /**
     * Perbarui data cabang dan catat audit log.
     */
    public function update(UpdateBranchRequest $request, Branch $branch): RedirectResponse
    {
        $oldData = $branch->toArray();
        $branch->update($request->validated());
        $newData = $branch->fresh()->toArray();

        ActivityLog::record(
            action: 'UPDATE',
            description: "Memperbarui informasi cabang: {$branch->name} ({$branch->code})",
            target: $branch,
            old: $oldData,
            new: $newData,
            branchId: $branch->id
        );

        return redirect()
            ->route('admin.branches.index')
            ->with('success', "Data cabang {$branch->name} berhasil diperbarui!");
    }

    /**
     * Ubah status keaktifan cabang secara cepat (Toggle).
     */
    public function toggleStatus(Branch $branch): RedirectResponse
    {
        $oldStatus = $branch->is_active;
        $branch->is_active = ! $oldStatus;
        $branch->save();

        $statusText = $branch->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            action: 'UPDATE',
            description: "Mengubah status cabang {$branch->name} menjadi {$statusText}",
            target: $branch,
            old: ['is_active' => $oldStatus],
            new: ['is_active' => $branch->is_active],
            branchId: $branch->id
        );

        return redirect()
            ->back()
            ->with('success', "Status cabang {$branch->name} berhasil {$statusText}!");
    }

    /**
     * Hapus cabang (jika tidak memiliki pengguna terkait).
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        if ($branch->users()->exists()) {
            return redirect()
                ->back()
                ->with('error', "Tidak dapat menghapus cabang {$branch->name} karena masih memiliki pengguna terkait. Silakan nonaktifkan cabang sebagai gantinya.");
        }

        $oldData = $branch->toArray();
        $branchName = $branch->name;
        $branch->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "Menghapus cabang: {$branchName}",
            target: null,
            old: $oldData,
            new: null,
            branchId: null
        );

        return redirect()
            ->route('admin.branches.index')
            ->with('success', "Cabang {$branchName} berhasil dihapus!");
    }
}
