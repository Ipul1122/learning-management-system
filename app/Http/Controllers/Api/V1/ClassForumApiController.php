<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreForumReplyRequest;
use App\Http\Requests\StoreForumThreadRequest;
use App\Http\Resources\ForumReplyResource;
use App\Http\Resources\ForumThreadResource;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassForumApiController extends Controller
{
    /**
     * Daftar thread forum kelas via API.
     */
    public function index(Request $request, TrainingClass $class): JsonResponse
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);

        $threads = ForumThread::where('class_id', $class->id)
            ->with(['author'])
            ->withCount('replies')
            ->pinnedFirst()
            ->paginate(15);

        return response()->json([
            'data' => ForumThreadResource::collection($threads),
            'meta' => [
                'current_page' => $threads->currentPage(),
                'last_page' => $threads->lastPage(),
                'total' => $threads->total(),
            ],
        ]);
    }

    /**
     * Buat thread baru via API.
     */
    public function storeThread(StoreForumThreadRequest $request, TrainingClass $class): JsonResponse
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);

        $thread = ForumThread::create([
            'class_id' => $class->id,
            'author_id' => $user->id,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'is_pinned' => false,
            'is_locked' => false,
        ]);

        return response()->json([
            'message' => 'Topik diskusi berhasil dibuat.',
            'data' => new ForumThreadResource($thread->load(['author', 'trainingClass'])),
        ], 201);
    }

    /**
     * Detail thread dan balasan berjenjang via API.
     */
    public function show(Request $request, TrainingClass $class, ForumThread $thread): JsonResponse
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);
        abort_unless($thread->class_id === $class->id, 404, 'Thread tidak ditemukan pada kelas ini.');

        $thread->load([
            'author',
            'trainingClass',
            'rootReplies.author',
            'rootReplies.children.author',
        ]);

        return response()->json([
            'data' => new ForumThreadResource($thread),
        ]);
    }

    /**
     * Kirim balasan thread via API.
     */
    public function storeReply(StoreForumReplyRequest $request, TrainingClass $class, ForumThread $thread): JsonResponse
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);
        abort_unless($thread->class_id === $class->id, 404, 'Thread tidak ditemukan pada kelas ini.');

        if ($thread->is_locked) {
            return response()->json(['message' => 'Diskusi ini telah dikunci oleh instruktur.'], 422);
        }

        $parentReplyId = $request->input('parent_reply_id');
        if ($parentReplyId) {
            $parent = ForumReply::where('id', $parentReplyId)->where('thread_id', $thread->id)->firstOrFail();
            $parentReplyId = $parent->id;
        }

        $reply = ForumReply::create([
            'thread_id' => $thread->id,
            'author_id' => $user->id,
            'parent_reply_id' => $parentReplyId,
            'reply_content' => $request->input('reply_content'),
        ]);

        return response()->json([
            'message' => 'Tanggapan berhasil dikirim.',
            'data' => new ForumReplyResource($reply->load(['author', 'children'])),
        ], 201);
    }

    /**
     * Sematkan / lepas semat thread via API.
     */
    public function togglePin(Request $request, TrainingClass $class, ForumThread $thread): JsonResponse
    {
        $user = $request->user();
        abort_unless($thread->canModerate($user), 403, 'Akses ditolak.');
        abort_unless($thread->class_id === $class->id, 404);

        $thread->update(['is_pinned' => ! $thread->is_pinned]);

        return response()->json([
            'message' => $thread->is_pinned ? 'Topik berhasil disematkan.' : 'Sematkan topik dibatalkan.',
            'is_pinned' => $thread->is_pinned,
        ]);
    }

    /**
     * Kunci / buka kunci thread via API.
     */
    public function toggleLock(Request $request, TrainingClass $class, ForumThread $thread): JsonResponse
    {
        $user = $request->user();
        abort_unless($thread->canModerate($user), 403, 'Akses ditolak.');
        abort_unless($thread->class_id === $class->id, 404);

        $thread->update(['is_locked' => ! $thread->is_locked]);

        return response()->json([
            'message' => $thread->is_locked ? 'Diskusi berhasil dikunci.' : 'Kunci diskusi dibuka.',
            'is_locked' => $thread->is_locked,
        ]);
    }

    /**
     * Otorisasi hak akses peserta/trainer ke forum kelas.
     */
    protected function authorizeClassAccess(TrainingClass $class, User $user): void
    {
        if ($user->hasRole('super-admin')) {
            return;
        }

        if ($user->hasRole('admin-cabang')) {
            abort_unless($user->branch_id === $class->branch_id, 403, 'Akses ditolak ke cabang lain.');

            return;
        }

        if ($user->hasRole('trainer')) {
            abort_unless($class->trainer_id === $user->id, 403, 'Bukan trainer pengampu kelas ini.');

            return;
        }

        abort_unless($class->isUserEnrolled($user->id), 403, 'Peserta tidak terdaftar di kelas ini.');
    }
}
