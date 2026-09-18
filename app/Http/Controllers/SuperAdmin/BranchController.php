<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreBranchRequest;
use App\Http\Requests\SuperAdmin\UpdateBranchRequest;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $existingBranches = Branch::with(['users' => function ($q) {
            $q->role('admin-cabang')->select('id', 'name', 'email', 'branch_id');
        }])->select('id', 'name', 'code', 'city', 'phone', 'address', 'is_active')
        ->orderBy('name')
        ->get()
        ->map(function ($b) {
            return [
                'id' => $b->id,
                'name' => $b->name,
                'code' => $b->code,
                'city' => $b->city,
                'phone' => $b->phone,
                'address' => $b->address,
                'is_active' => (bool) $b->is_active,
                'admin_count' => $b->users->count(),
                'admin_names' => $b->users->pluck('name')->implode(', '),
            ];
        });

        return view('super-admin.branches.create', compact('existingBranches'));
    }

    /**
     * Simpan cabang baru ke database dan catat audit log.
     * Sekaligus membuat akun Admin Cabang jika diisi, atau menambah admin pada cabang yang sudah ada.
     */
    public function store(StoreBranchRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $result = DB::transaction(function () use ($validated, $request) {
            // Skenario A: Menggunakan cabang yang sudah ada untuk menambah admin baru
            if ($request->filled('existing_branch_id')) {
                $branch = Branch::findOrFail($request->input('existing_branch_id'));

                $admin = User::create([
                    'name' => $validated['admin_name'],
                    'email' => $validated['admin_email'],
                    'password' => Hash::make($validated['admin_password']),
                    'phone_number' => $validated['admin_phone_number'] ?? null,
                    'status' => $validated['admin_status'] ?? 'active',
                    'branch_id' => $branch->id,
                ]);

                $admin->assignRole('admin-cabang');

                ActivityLog::record(
                    action: 'CREATE',
                    description: "Menambahkan akun Admin Cabang: {$admin->name} ({$admin->email}) untuk cabang terdaftar {$branch->name} ({$branch->code})",
                    target: $admin,
                    old: null,
                    new: $admin->only(['id', 'name', 'email', 'branch_id', 'status']),
                    branchId: $branch->id
                );

                return [$branch, $admin, true];
            }

            // Skenario B: Membuat kantor cabang baru
            $branch = Branch::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            ActivityLog::record(
                action: 'CREATE',
                description: "Membuat cabang baru: {$branch->name} ({$branch->code})",
                target: $branch,
                old: null,
                new: $branch->toArray(),
                branchId: $branch->id
            );

            // Buat Akun Admin Cabang jika diisi
            $admin = null;
            $hasAdmin = $request->boolean('create_admin_account') || ! empty($validated['admin_name']);
            if ($hasAdmin && ! empty($validated['admin_email'])) {
                $admin = User::create([
                    'name' => $validated['admin_name'],
                    'email' => $validated['admin_email'],
                    'password' => Hash::make($validated['admin_password']),
                    'phone_number' => $validated['admin_phone_number'] ?? null,
                    'status' => $validated['admin_status'] ?? 'active',
                    'branch_id' => $branch->id,
                ]);

                $admin->assignRole('admin-cabang');

                ActivityLog::record(
                    action: 'CREATE',
                    description: "Membuat akun Admin Cabang: {$admin->name} ({$admin->email}) untuk {$branch->name}",
                    target: $admin,
                    old: null,
                    new: $admin->only(['id', 'name', 'email', 'branch_id', 'status']),
                    branchId: $branch->id
                );
            }

            return [$branch, $admin, false];
        });

        [$branch, $admin, $isReused] = $result;

        if ($request->input('action') === 'save_and_add_another') {
            $addMsg = $admin
                ? "Admin {$admin->name} ({$admin->email}) berhasil ditambahkan ke {$branch->name}!"
                : "Cabang {$branch->name} berhasil disimpan!";
            return redirect()
                ->route('admin.branches.create', ['branch_id' => $branch->id])
                ->with('success', "{$addMsg} Silakan isi formulir untuk mendaftarkan akun admin berikutnya.");
        }

        if ($isReused) {
            $message = "Berhasil menambahkan akun Admin Cabang {$admin->name} ({$admin->email}) untuk kantor cabang {$branch->name}!";
        } else {
            $message = $admin
                ? "Kantor Cabang {$branch->name} dan akun Admin Cabang {$admin->name} ({$admin->email}) berhasil didaftarkan!"
                : "Cabang {$branch->name} berhasil ditambahkan!";
        }

        return redirect()
            ->route('admin.branches.index')
            ->with('success', $message);
    }

    /**
     * Tampilkan formulir edit cabang.
     */
    public function edit(Branch $branch): View
    {
        $existingBranches = Branch::where('id', '!=', $branch->id)->select('id', 'name', 'code')->get();
        $admin = $branch->users()->role('admin-cabang')->first();

        return view('super-admin.branches.edit', compact('branch', 'existingBranches', 'admin'));
    }

    /**
     * Perbarui data cabang dan catat audit log.
     * Sekaligus memperbarui atau mendaftarkan akun Admin Cabang.
     */
    public function update(UpdateBranchRequest $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validated();

        $result = DB::transaction(function () use ($validated, $request, $branch) {
            $oldData = $branch->toArray();
            $branch->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);
            $newData = $branch->fresh()->toArray();

            ActivityLog::record(
                action: 'UPDATE',
                description: "Memperbarui informasi cabang: {$branch->name} ({$branch->code})",
                target: $branch,
                old: $oldData,
                new: $newData,
                branchId: $branch->id
            );

            // Periksa akun admin cabang
            $admin = $branch->users()->role('admin-cabang')->first();
            $adminMessage = '';

            if ($admin) {
                // Perbarui akun admin yang ada jika data admin terisi
                if (! empty($validated['admin_name']) && ! empty($validated['admin_email'])) {
                    $adminOld = $admin->only(['name', 'email', 'phone_number', 'status']);
                    $adminData = [
                        'name' => $validated['admin_name'],
                        'email' => $validated['admin_email'],
                        'phone_number' => $validated['admin_phone_number'] ?? null,
                        'status' => $validated['admin_status'] ?? $admin->status,
                    ];

                    if (! empty($validated['admin_password'])) {
                        $adminData['password'] = Hash::make($validated['admin_password']);
                    }

                    $admin->update($adminData);

                    ActivityLog::record(
                        action: 'UPDATE',
                        description: "Memperbarui akun Admin Cabang: {$admin->name} ({$admin->email}) untuk {$branch->name}",
                        target: $admin,
                        old: $adminOld,
                        new: $admin->only(['name', 'email', 'phone_number', 'status']),
                        branchId: $branch->id
                    );

                    $adminMessage = " dan akun Admin Cabang {$admin->name}";
                }
            } else {
                // Jika belum ada admin dan memilih untuk membuat akun admin baru
                $hasAdmin = $request->boolean('create_admin_account') || ! empty($validated['admin_name']);
                if ($hasAdmin && ! empty($validated['admin_email']) && ! empty($validated['admin_password'])) {
                    $newAdmin = User::create([
                        'name' => $validated['admin_name'],
                        'email' => $validated['admin_email'],
                        'password' => Hash::make($validated['admin_password']),
                        'phone_number' => $validated['admin_phone_number'] ?? null,
                        'status' => $validated['admin_status'] ?? 'active',
                        'branch_id' => $branch->id,
                    ]);

                    $newAdmin->assignRole('admin-cabang');

                    ActivityLog::record(
                        action: 'CREATE',
                        description: "Membuat akun Admin Cabang baru: {$newAdmin->name} ({$newAdmin->email}) untuk {$branch->name}",
                        target: $newAdmin,
                        old: null,
                        new: $newAdmin->only(['id', 'name', 'email', 'branch_id', 'status']),
                        branchId: $branch->id
                    );

                    $adminMessage = " dan mendaftarkan Admin Cabang {$newAdmin->name}";
                }
            }

            return [$branch, $adminMessage];
        });

        [$branch, $adminMessage] = $result;

        return redirect()
            ->route('admin.branches.index')
            ->with('success', "Data cabang {$branch->name}{$adminMessage} berhasil diperbarui!");
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
