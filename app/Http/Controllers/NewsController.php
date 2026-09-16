<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Tampilkan daftar feed berita dan pengumuman.
     */
    public function index(Request $request): View
    {
        $query = NewsPost::published()->with(['branch', 'author']);

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter berdasarkan cabang (atau global)
        if ($request->filled('branch_id')) {
            $query->forBranch($request->input('branch_id'));
        }

        // Pencarian judul atau isi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $newsPosts = $query->latest('published_at')->latest('id')->paginate(9)->withQueryString();

        $categories = ['Pengumuman', 'Akademik', 'Kegiatan', 'Tips & Trik'];
        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        // Ambil 1 artikel headline / terpopuler untuk banner atas
        $featuredPost = NewsPost::published()->latest('published_at')->first();

        return view('news.index', compact('newsPosts', 'categories', 'branches', 'featuredPost'));
    }

    /**
     * Tampilkan detail artikel berita / pengumuman.
     */
    public function show(string $slug): View
    {
        $post = NewsPost::published()
            ->where('slug', $slug)
            ->with(['branch', 'author'])
            ->firstOrFail();

        // 3 artikel rekomendasi terkait
        $relatedPosts = NewsPost::published()
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->where('category', $post->category)
                    ->orWhere('branch_id', $post->branch_id);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('news.show', compact('post', 'relatedPosts'));
    }
}
