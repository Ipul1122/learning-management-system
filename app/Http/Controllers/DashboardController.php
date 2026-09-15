<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dispatcher utama: Mengarahkan pengguna ke dashboard sesuai perannya.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('admin-cabang')) {
            return redirect()->route('cabang.dashboard');
        }

        if ($user->hasRole('trainer')) {
            return redirect()->route('trainer.dashboard');
        }

        return $this->pesertaDashboard($request);
    }

    /**
     * Dashboard untuk Super Administrator.
     */
    public function adminDashboard(Request $request): View
    {
        $branchesCount = Branch::count();
        $adminsCount = User::role('admin-cabang')->count();
        $trainersCount = User::role('trainer')->count();
        $studentsCount = User::role('peserta')->count();

        return view('super-admin.dashboard', compact(
            'branchesCount',
            'adminsCount',
            'trainersCount',
            'studentsCount'
        ));
    }

    /**
     * Dashboard untuk Admin Cabang.
     */
    public function cabangDashboard(Request $request): View
    {
        $user = $request->user();
        $branch = $user->branch;
        $branchId = $branch?->id;

        $trainersCount = $branchId ? User::role('trainer')->where('branch_id', $branchId)->count() : 0;
        $classesCount = $branchId ? TrainingClass::where('branch_id', $branchId)->count() : 0;
        $activeClassesCount = $branchId ? TrainingClass::where('branch_id', $branchId)->whereIn('status', ['open', 'ongoing'])->count() : 0;
        $recentClasses = $branchId ? TrainingClass::where('branch_id', $branchId)->with(['trainer', 'sessions'])->latest()->take(5)->get() : collect();
        $recentLogs = $branchId ? ActivityLog::where('branch_id', $branchId)->with('user')->latest()->take(5)->get() : collect();

        return view('admin-cabang.dashboard', compact(
            'user',
            'branch',
            'trainersCount',
            'classesCount',
            'activeClassesCount',
            'recentClasses',
            'recentLogs'
        ));
    }

    /**
     * Dashboard untuk Trainer.
     */
    public function trainerDashboard(Request $request): View
    {
        $user = $request->user();
        $branch = $user->branch;

        return view('trainer.dashboard', compact('user', 'branch'));
    }

    /**
     * Dashboard untuk Peserta (Default).
     */
    public function pesertaDashboard(Request $request): View
    {
        $user = $request->user();

        return view('peserta.dashboard', compact('user'));
    }
}
