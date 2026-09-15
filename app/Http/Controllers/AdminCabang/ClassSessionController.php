<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreClassSessionRequest;
use App\Http\Requests\AdminCabang\UpdateClassSessionRequest;
use App\Models\ActivityLog;
use App\Models\ClassSession;
use App\Models\TrainingClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassSessionController extends Controller
{
    /**
     * Tambahkan jadwal sesi pembelajaran baru ke dalam kelas.
     */
    public function store(StoreClassSessionRequest $request, TrainingClass $class): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);

        $data = $request->validated();
        $data['class_id'] = $class->id;
        $data['minute_duration'] = $data['jp_duration'] * 45; // Konversi 1 JP = 45 menit
        $data['created_by_user_id'] = $request->user()->id;

        $session = ClassSession::create($data);

        ActivityLog::record(
            action: 'CREATE',
            description: "Menambahkan sesi pertemuan: {$session->title} ({$session->jp_duration} JP) pada kelas {$class->title}",
            target: $session,
            old: null,
            new: $session->toArray(),
            branchId: $class->branch_id
        );

        return redirect()
            ->route('cabang.classes.show', $class)
            ->with('success', "Sesi {$session->title} ({$session->jp_duration} JP / {$session->minute_duration} Menit) berhasil ditambahkan!");
    }

    /**
     * Tampilkan formulir edit sesi pertemuan.
     */
    public function edit(Request $request, TrainingClass $class, ClassSession $session): View
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);
        abort_unless($session->class_id === $class->id, 404);

        return view('admin-cabang.sessions.edit', compact('class', 'session', 'branch'));
    }

    /**
     * Perbarui data sesi pertemuan dan link Zoom.
     */
    public function update(UpdateClassSessionRequest $request, TrainingClass $class, ClassSession $session): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);
        abort_unless($session->class_id === $class->id, 404);

        $oldData = $session->toArray();
        $data = $request->validated();
        $data['minute_duration'] = $data['jp_duration'] * 45;

        $session->update($data);
        $newData = $session->fresh()->toArray();

        ActivityLog::record(
            action: 'UPDATE',
            description: "Memperbarui jadwal sesi pertemuan: {$session->title} pada kelas {$class->title}",
            target: $session,
            old: $oldData,
            new: $newData,
            branchId: $class->branch_id
        );

        return redirect()
            ->route('cabang.classes.show', $class)
            ->with('success', "Jadwal sesi {$session->title} berhasil diperbarui!");
    }

    /**
     * Hapus jadwal sesi pertemuan.
     */
    public function destroy(Request $request, TrainingClass $class, ClassSession $session): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeClassAccess($class, $branch?->id);
        abort_unless($session->class_id === $class->id, 404);

        $sessionTitle = $session->title;
        $oldData = $session->toArray();
        $session->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "Menghapus sesi pertemuan: {$sessionTitle} dari kelas {$class->title}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $class->branch_id
        );

        return redirect()
            ->route('cabang.classes.show', $class)
            ->with('success', "Sesi {$sessionTitle} berhasil dihapus!");
    }

    protected function authorizeClassAccess(TrainingClass $class, ?int $branchId): void
    {
        abort_unless(
            $branchId && $class->branch_id === $branchId,
            403,
            'Anda tidak memiliki wewenang mengelola sesi kelas dari cabang lain.'
        );
    }
}
