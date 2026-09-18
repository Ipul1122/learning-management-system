<?php

namespace App\Http\Controllers;

use App\Http\Requests\Forum\StoreForumReplyRequest;
use App\Http\Requests\Forum\StoreForumThreadRequest;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassForumController extends Controller
{
    /**
     * Tampilkan daftar thread diskusi pada kelas.
     */
    public function index(Request $request, TrainingClass $class): View
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);

        $threads = ForumThread::where('class_id', $class->id)
            ->with(['author'])
            ->withCount('replies')
            ->pinnedFirst()
            ->paginate(15)
            ->withQueryString();

        return view('forums.index', compact('class', 'threads'));
    }

    /**
     * Form pembuatan topik diskusi baru.
     */
    public function create(Request $request, TrainingClass $class): View
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);

        return view('forums.create', compact('class'));
    }

    /**
     * Simpan topik diskusi baru.
     */
    public function store(StoreForumThreadRequest $request, TrainingClass $class): RedirectResponse
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

        // Gamifikasi: +15 XP Partisipasi Forum & Cek Lencana Aktivis Diskusi
        app(\App\Services\GamificationService::class)->awardPoints(
            $user,
            15,
            'forum',
            "Membuat Topik Diskusi: '{$thread->title}'",
            $thread->id
        );

        return redirect()
            ->route('classes.forum.show', [$class, $thread])
            ->with('success', 'Topik diskusi berhasil diterbitkan!');
    }

    /**
     * Tampilkan detail thread diskusi dan seluruh balasan berjenjang.
     */
    public function show(Request $request, TrainingClass $class, ForumThread $thread): View
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);
        abort_unless($thread->class_id === $class->id, 404);

        $thread->load([
            'author',
            'trainingClass.trainer',
            'rootReplies.author',
            'rootReplies.children.author',
        ]);

        return view('forums.show', compact('class', 'thread'));
    }

    /**
     * Kirim balasan baru pada thread (atau respon bersarang pada balasan lain).
     */
    public function storeReply(StoreForumReplyRequest $request, TrainingClass $class, ForumThread $thread): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeClassAccess($class, $user);
        abort_unless($thread->class_id === $class->id, 404);

        if ($thread->is_locked) {
            return redirect()->route('classes.forum.show', [$class, $thread])
                ->with('error', 'Diskusi ini telah dikunci oleh instruktur dan tidak menerima tanggapan baru.');
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

        // Gamifikasi: +15 XP Balasan Forum & Cek Lencana Aktivis Diskusi
        app(\App\Services\GamificationService::class)->awardPoints(
            $user,
            15,
            'forum',
            "Membalas Topik Diskusi: '{$thread->title}'",
            $reply->id
        );

        return redirect()->route('classes.forum.show', [$class, $thread])
            ->with('success', 'Tanggapan berhasil dikirimkan!');
    }

    /**
     * Sematkan atau lepas semat topik (khusus Trainer / Admin).
     */
    public function togglePin(Request $request, TrainingClass $class, ForumThread $thread): RedirectResponse
    {
        $user = $request->user();
        abort_unless($thread->canModerate($user), 403, 'Anda tidak memiliki hak moderasi untuk menyematkan topik ini.');
        abort_unless($thread->class_id === $class->id, 404);

        $thread->update([
            'is_pinned' => ! $thread->is_pinned,
        ]);

        $status = $thread->is_pinned ? 'Topik berhasil disematkan di bagian teratas.' : 'Status semat topik dibatalkan.';

        return redirect()->route('classes.forum.show', [$class, $thread])
            ->with('success', $status);
    }

    /**
     * Kunci atau buka kunci thread diskusi (khusus Trainer / Admin).
     */
    public function toggleLock(Request $request, TrainingClass $class, ForumThread $thread): RedirectResponse
    {
        $user = $request->user();
        abort_unless($thread->canModerate($user), 403, 'Anda tidak memiliki hak moderasi untuk mengunci topik ini.');
        abort_unless($thread->class_id === $class->id, 404);

        $thread->update([
            'is_locked' => ! $thread->is_locked,
        ]);

        $status = $thread->is_locked ? 'Diskusi topik ini telah dikunci.' : 'Kunci topik dibuka kembali.';

        return redirect()->route('classes.forum.show', [$class, $thread])
            ->with('success', $status);
    }

    /**
     * Hapus topik diskusi oleh pembuat atau moderator.
     */
    public function destroy(Request $request, TrainingClass $class, ForumThread $thread): RedirectResponse
    {
        $user = $request->user();
        abort_unless($thread->isAuthor($user) || $thread->canModerate($user), 403, 'Anda tidak memiliki hak untuk menghapus topik ini.');
        abort_unless($thread->class_id === $class->id, 404);

        $thread->delete();

        return redirect()
            ->route('classes.forum.index', $class)
            ->with('success', 'Topik diskusi berhasil dihapus.');
    }

    /**
     * Validasi hak akses pengguna terhadap forum kelas.
     */
    protected function authorizeClassAccess(TrainingClass $class, User $user): void
    {
        if ($user->hasRole('super-admin')) {
            return;
        }

        if ($user->hasRole('admin-cabang')) {
            abort_unless($user->branch_id === $class->branch_id, 403, 'Anda tidak memiliki akses ke forum kelas cabang lain.');

            return;
        }

        if ($user->hasRole('trainer')) {
            abort_unless($class->trainer_id === $user->id, 403, 'Anda bukan instruktur pengampu kelas pelatihan ini.');

            return;
        }

        // Peserta
        abort_unless($class->isUserEnrolled($user->id), 403, 'Anda harus terdaftar di kelas ini untuk mengakses forum diskusi komunitas.');
    }
}
