<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsPostRequest;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\NewsPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminNewsController extends Controller
{
    /**
     * Tampilkan daftar manajemen berita & pengumuman.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = NewsPost::with(['branch', 'author']);

        // Isolasi multi-tenant: Admin Cabang hanya melihat berita cabangnya
        if ($user->hasRole('admin-cabang')) {
            $query->where('branch_id', $user->branch_id);
        } elseif ($request->filled('branch_id')) {
            if ($request->input('branch_id') === 'global') {
                $query->whereNull('branch_id');
            } else {
                $query->where('branch_id', $request->input('branch_id'));
            }
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $posts = $query->latest('id')->paginate(10)->withQueryString();
        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        return view('admin.news.index', compact('posts', 'branches'));
    }

    /**
     * Form pembuatan berita baru.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $categories = ['Pengumuman', 'Akademik', 'Kegiatan', 'Tips & Trik'];

        return view('admin.news.form', compact('branches', 'categories'));
    }

    /**
     * Simpan publikasi berita baru.
     */
    public function store(StoreNewsPostRequest $request): RedirectResponse
    {
        $user = $request->user();

        $branchId = $request->input('branch_id');
        if ($user->hasRole('admin-cabang')) {
            $branchId = $user->branch_id;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('news', 'public');
        }

        $baseSlug = Str::slug($request->input('title'));
        $slug = $baseSlug;
        $counter = 1;
        while (NewsPost::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $post = NewsPost::create([
            'branch_id' => $branchId,
            'author_id' => $user->id,
            'title' => $request->input('title'),
            'slug' => $slug,
            'category' => $request->input('category'),
            'content' => $request->input('content'),
            'thumbnail' => $thumbnailPath,
            'is_published' => $request->boolean('is_published', true),
            'published_at' => $request->input('published_at') ? now()->parse($request->input('published_at')) : now(),
        ]);

        ActivityLog::record(
            action: 'CREATE_NEWS',
            description: "{$user->name} menerbitkan berita '{$post->title}'",
            target: $post,
            old: null,
            new: $post->toArray(),
            branchId: $branchId
        );

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dipublikasikan!');
    }

    /**
     * Form edit berita.
     */
    public function edit(Request $request, NewsPost $news): View
    {
        $user = $request->user();
        if ($user->hasRole('admin-cabang') && $news->branch_id !== $user->branch_id) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit berita cabang lain.');
        }

        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $categories = ['Pengumuman', 'Akademik', 'Kegiatan', 'Tips & Trik'];

        return view('admin.news.form', [
            'post' => $news,
            'branches' => $branches,
            'categories' => $categories,
        ]);
    }

    /**
     * Perbarui data berita.
     */
    public function update(StoreNewsPostRequest $request, NewsPost $news): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('admin-cabang') && $news->branch_id !== $user->branch_id) {
            abort(403, 'Anda tidak memiliki hak untuk memperbarui berita cabang lain.');
        }

        $branchId = $news->branch_id;
        if ($user->hasRole('super-admin')) {
            $branchId = $request->input('branch_id');
        }

        $thumbnailPath = $news->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('news', 'public');
        }

        $old = $news->toArray();

        $news->update([
            'branch_id' => $branchId,
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'content' => $request->input('content'),
            'thumbnail' => $thumbnailPath,
            'is_published' => $request->boolean('is_published', true),
            'published_at' => $request->input('published_at') ? now()->parse($request->input('published_at')) : $news->published_at,
        ]);

        ActivityLog::record(
            action: 'UPDATE_NEWS',
            description: "{$user->name} memperbarui artikel '{$news->title}'",
            target: $news,
            old: $old,
            new: $news->fresh()->toArray(),
            branchId: $branchId
        );

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Hapus artikel berita.
     */
    public function destroy(Request $request, NewsPost $news): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('admin-cabang') && $news->branch_id !== $user->branch_id) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus berita cabang lain.');
        }

        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        $old = $news->toArray();
        $title = $news->title;
        $branchId = $news->branch_id;
        $news->delete();

        ActivityLog::record(
            action: 'DELETE_NEWS',
            description: "{$user->name} menghapus artikel berita '{$title}'",
            target: null,
            old: $old,
            new: null,
            branchId: $branchId
        );

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
