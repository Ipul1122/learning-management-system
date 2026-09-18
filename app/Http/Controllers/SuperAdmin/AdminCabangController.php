<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreAdminCabangRequest;
use App\Http\Requests\SuperAdmin\UpdateAdminCabangRequest;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminCabangController extends Controller
{
    /**
     * Tampilkan daftar seluruh akun Admin Cabang.
     */
    public function index(Request $request): View
    {
        $query = User::adminCabangs()->with('branch');

        // Pencarian nama, email, nomor telepon
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan cabang penugasan
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        // Filter status keaktifan akun
        if ($request->filled('status') && in_array($request->input('status'), ['active', 'inactive'])) {
            $query->where('status', $request->input('status'));
        }

        $admins = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => User::adminCabangs()->count(),
            'active' => User::adminCabangs()->where('status', 'active')->count(),
            'inactive' => User::adminCabangs()->where('status', 'inactive')->count(),
        ];

        $branches = Branch::orderBy('name')->get();

        return view('super-admin.admins.index', compact('admins', 'stats', 'branches'));
    }

    /**
     * Tampilkan formulir pembuatan akun Admin Cabang baru.
     * Dialihkan ke pembuatan cabang terintegrasi untuk UX yang lebih nyaman.
     */
    public function create(): RedirectResponse
    {
        return redirect()
            ->route('admin.branches.create')
            ->with('info', 'Pendaftaran Admin Cabang kini terintegrasi langsung pada menu Tambah Cabang Baru.');
    }

    /**
     * Simpan akun Admin Cabang baru ke database.
     */
    public function store(StoreAdminCabangRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $admin = User::create($data);
        $admin->assignRole('admin-cabang');

        $branchName = $admin->branch?->name ?? 'Cabang';

        ActivityLog::record(
            action: 'CREATE',
            description: "Membuat akun Admin Cabang: {$admin->name} ({$admin->email}) untuk {$branchName}",
            target: $admin,
            old: null,
            new: $admin->only(['id', 'name', 'email', 'branch_id', 'status']),
            branchId: $admin->branch_id
        );

        return redirect()
            ->route('admin.admins.index')
            ->with('success', "Akun Admin Cabang {$admin->name} berhasil dibuat!");
    }

    /**
     * Tampilkan formulir edit akun Admin Cabang.
     */
    public function edit(User $admin): View
    {
        abort_unless($admin->hasRole('admin-cabang'), 404);

        $branches = Branch::orderBy('name')->get();

        return view('super-admin.admins.edit', compact('admin', 'branches'));
    }

    /**
     * Perbarui profil atau penugasan cabang akun Admin Cabang.
     */
    public function update(UpdateAdminCabangRequest $request, User $admin): RedirectResponse
    {
        abort_unless($admin->hasRole('admin-cabang'), 404);

        $oldData = $admin->only(['name', 'email', 'branch_id', 'phone_number', 'status']);
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $admin->update($data);
        $newData = $admin->fresh()->only(['name', 'email', 'branch_id', 'phone_number', 'status']);

        ActivityLog::record(
            action: 'UPDATE',
            description: "Memperbarui akun Admin Cabang: {$admin->name} ({$admin->email})",
            target: $admin,
            old: $oldData,
            new: $newData,
            branchId: $admin->branch_id
        );

        return redirect()
            ->route('admin.admins.index')
            ->with('success', "Akun Admin Cabang {$admin->name} berhasil diperbarui!");
    }

    /**
     * Ubah status keaktifan akun Admin Cabang (Toggle).
     */
    public function toggleStatus(User $admin): RedirectResponse
    {
        abort_unless($admin->hasRole('admin-cabang'), 404);

        $oldStatus = $admin->status;
        $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';

        $admin->status = $newStatus;
        $admin->save();

        $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            action: 'UPDATE',
            description: "Mengubah status akun Admin Cabang {$admin->name} menjadi {$statusText}",
            target: $admin,
            old: ['status' => $oldStatus],
            new: ['status' => $newStatus],
            branchId: $admin->branch_id
        );

        return redirect()
            ->back()
            ->with('success', "Status akun {$admin->name} berhasil {$statusText}!");
    }

    /**
     * Hapus akun Admin Cabang.
     */
    public function destroy(User $admin): RedirectResponse
    {
        abort_unless($admin->hasRole('admin-cabang'), 404);

        $adminName = $admin->name;
        $oldData = $admin->only(['id', 'name', 'email', 'branch_id']);
        $admin->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "Menghapus akun Admin Cabang: {$adminName}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $oldData['branch_id']
        );

        return redirect()
            ->route('admin.admins.index')
            ->with('success', "Akun Admin Cabang {$adminName} berhasil dihapus!");
    }
}
