<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\StoreNewsPostRequest;
use App\Http\Resources\NewsPostResource;
use App\Models\NewsPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsApiController extends Controller
{
    /**
     * Feed berita & pengumuman publik / peserta via API.
     */
    public function index(Request $request): JsonResponse
    {
        $query = NewsPost::published()->with(['branch', 'author']);

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('branch_id')) {
            $query->forBranch($request->input('branch_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->latest('published_at')->paginate(15)->withQueryString();

        return response()->json([
            'data' => NewsPostResource::collection($news),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'total' => $news->total(),
            ],
        ]);
    }

    /**
     * Detail berita via API.
     */
    public function show(string $slug): JsonResponse
    {
        $post = NewsPost::published()
            ->where('slug', $slug)
            ->with(['branch', 'author'])
            ->firstOrFail();

        return response()->json([
            'data' => new NewsPostResource($post),
        ]);
    }

    /**
     * Publikasi berita via API (Admin / Super Admin).
     */
    public function store(StoreNewsPostRequest $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user->hasRole(['super-admin', 'admin-cabang', 'trainer']), 403, 'Akses ditolak.');

        $branchId = $request->input('branch_id');
        if ($user->hasRole('admin-cabang') || $user->hasRole('trainer')) {
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

        return response()->json([
            'message' => 'Berita berhasil dipublikasikan.',
            'data' => new NewsPostResource($post->load(['branch', 'author'])),
        ], 201);
    }
}
