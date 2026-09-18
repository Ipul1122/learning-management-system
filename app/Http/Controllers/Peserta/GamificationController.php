<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GamificationController extends Controller
{
    /**
     * Tampilkan Papan Peringkat (Leaderboard) Peserta Nasional & Filter per Cabang.
     */
    public function leaderboard(Request $request): View
    {
        $user = $request->user();

        $query = User::role('peserta')
            ->with(['branch', 'badges'])
            ->orderByDesc('total_points')
            ->orderBy('name');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        // Ambil Top 3 untuk Podium Juara
        $topThree = (clone $query)->take(3)->get();

        // Paginasi seluruh peserta
        $leaderboard = $query->paginate(15)->withQueryString();

        // Hitung peringkat pengguna yang sedang login (secara global)
        $myGlobalRank = User::role('peserta')
            ->where('total_points', '>', $user->total_points ?? 0)
            ->count() + 1;

        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        return view('peserta.gamification.leaderboard', compact(
            'leaderboard',
            'topThree',
            'myGlobalRank',
            'branches'
        ));
    }

    /**
     * Tampilkan Galeri Lencana Prestasi (Badges) dan Riwayat Poin XP Pengguna.
     */
    public function badges(Request $request): View
    {
        $user = $request->user();

        $allBadges = Badge::orderBy('xp_reward', 'asc')->get();
        $userBadges = $user->badges()->withPivot('earned_at')->get()->keyBy('id');

        $recentTransactions = $user->pointTransactions()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peserta.gamification.badges', compact(
            'allBadges',
            'userBadges',
            'recentTransactions'
        ));
    }
}
