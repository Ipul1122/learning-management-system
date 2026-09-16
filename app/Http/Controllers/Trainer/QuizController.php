<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\StoreQuizRequest;
use App\Http\Requests\Trainer\UpdateQuizRequest;
use App\Models\ActivityLog;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\TrainingClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Tampilkan daftar paket kuis pada kelas yang diampu oleh trainer.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $branch = $user->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        $query = Quiz::whereHas('class', function ($q) use ($user, $branch) {
            $q->where('branch_id', $branch->id)
                ->where('trainer_id', $user->id);
        })->with(['class', 'creator'])->withCount('questions');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->input('class_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $quizzes = $query->latest()->paginate(12)->withQueryString();

        // Daftar kelas yang diampu trainer untuk filter
        $myClasses = TrainingClass::where('branch_id', $branch->id)
            ->where('trainer_id', $user->id)
            ->orderBy('title')
            ->get();

        $stats = [
            'total_quizzes' => Quiz::whereHas('class', fn ($q) => $q->where('branch_id', $branch->id)->where('trainer_id', $user->id))->count(),
            'total_classes' => $myClasses->count(),
            'total_questions_bank' => Question::where('branch_id', $branch->id)->count(),
        ];

        return view('trainer.quizzes.index', compact('quizzes', 'myClasses', 'stats', 'branch'));
    }

    /**
     * Formulir pembuatan paket kuis baru.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $branch = $user->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        $trainingClasses = TrainingClass::where('branch_id', $branch->id)
            ->where('trainer_id', $user->id)
            ->orderBy('title')
            ->get();
        $classes = $trainingClasses;

        $questions = Question::where('branch_id', $branch->id)
            ->with(['options'])
            ->latest()
            ->get();

        return view('trainer.quizzes.create', compact('trainingClasses', 'classes', 'questions', 'branch'));
    }

    /**
     * Simpan kuis baru beserta butir-butir soal yang dipilih.
     */
    public function store(StoreQuizRequest $request): RedirectResponse
    {
        $user = $request->user();
        $branch = $user->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        $quiz = DB::transaction(function () use ($request, $user) {
            $quiz = Quiz::create([
                'class_id' => $request->input('class_id'),
                'creator_id' => $user->id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'time_limit_minutes' => $request->input('time_limit_minutes', 30),
                'passing_grade' => $request->input('passing_grade', 75.00),
                'is_randomized' => $request->boolean('is_randomized'),
                'max_attempts' => $request->input('max_attempts', 1),
            ]);

            $questionIds = $request->input('questions', []);
            $syncData = [];
            foreach ($questionIds as $index => $qId) {
                $syncData[$qId] = ['order_number' => $index + 1];
            }
            $quiz->questions()->sync($syncData);

            return $quiz;
        });

        ActivityLog::record(
            action: 'CREATE',
            description: "Membuat kuis '{$quiz->title}' untuk kelas #{$quiz->class_id} dengan {$quiz->questions()->count()} butir soal",
            target: $quiz,
            old: null,
            new: $quiz->load('questions')->toArray(),
            branchId: $branch->id
        );

        return redirect()
            ->route('trainer.quizzes.index')
            ->with('success', 'Paket kuis berhasil dibuat dan dipublikasikan ke kelas!');
    }

    /**
     * Tampilkan rincian kuis dan butir-butir soal di dalamnya.
     */
    public function show(Request $request, Quiz $quiz): View
    {
        $user = $request->user();
        $this->authorizeQuizAccess($quiz, $user);

        $quiz->load(['class', 'creator', 'questions.options']);

        return view('trainer.quizzes.show', [
            'quiz' => $quiz,
            'branch' => $user->branch,
        ]);
    }

    /**
     * Formulir perbaikan konfigurasi kuis.
     */
    public function edit(Request $request, Quiz $quiz): View
    {
        $user = $request->user();
        $this->authorizeQuizAccess($quiz, $user);

        $branch = $user->branch;
        $trainingClasses = TrainingClass::where('branch_id', $branch->id)
            ->where('trainer_id', $user->id)
            ->orderBy('title')
            ->get();
        $classes = $trainingClasses;

        $questions = Question::where('branch_id', $branch->id)
            ->with('options')
            ->latest()
            ->get();

        $selectedQuestionIds = $quiz->questions()->pluck('questions.id')->toArray();

        return view('trainer.quizzes.edit', compact('quiz', 'trainingClasses', 'classes', 'questions', 'selectedQuestionIds', 'branch'));
    }

    /**
     * Perbarui konfigurasi kuis dan susunan butir soal.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeQuizAccess($quiz, $user);
        $branch = $user->branch;

        $oldData = $quiz->load('questions')->toArray();

        DB::transaction(function () use ($request, $quiz) {
            $quiz->update([
                'class_id' => $request->input('class_id'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'time_limit_minutes' => $request->input('time_limit_minutes'),
                'passing_grade' => $request->input('passing_grade'),
                'is_randomized' => $request->boolean('is_randomized'),
                'max_attempts' => $request->input('max_attempts'),
            ]);

            $questionIds = $request->input('questions', []);
            $syncData = [];
            foreach ($questionIds as $index => $qId) {
                $syncData[$qId] = ['order_number' => $index + 1];
            }
            $quiz->questions()->sync($syncData);
        });

        $newData = $quiz->fresh()->load('questions')->toArray();

        ActivityLog::record(
            action: 'UPDATE',
            description: "Memperbarui konfigurasi kuis '{$quiz->title}' pada kelas #{$quiz->class_id}",
            target: $quiz,
            old: $oldData,
            new: $newData,
            branchId: $branch->id
        );

        return redirect()
            ->route('trainer.quizzes.index')
            ->with('success', 'Konfigurasi kuis berhasil diperbarui!');
    }

    /**
     * Hapus kuis.
     */
    public function destroy(Request $request, Quiz $quiz): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeQuizAccess($quiz, $user);
        $branch = $user->branch;

        $oldData = $quiz->toArray();
        $quizTitle = $quiz->title;
        $quiz->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "Menghapus kuis '{$quizTitle}'",
            target: null,
            old: $oldData,
            new: null,
            branchId: $branch->id
        );

        return redirect()
            ->route('trainer.quizzes.index')
            ->with('success', 'Paket kuis berhasil dihapus!');
    }

    /**
     * Verifikasi kepemilikan kuis oleh trainer cabang bersangkutan.
     */
    protected function authorizeQuizAccess(Quiz $quiz, User $user): void
    {
        $quiz->loadMissing('class');

        $isSameBranch = $user->branch_id && $quiz->class && $quiz->class->branch_id === $user->branch_id;
        $isClassTrainer = $quiz->class && $quiz->class->trainer_id === $user->id;
        $isCreator = $quiz->creator_id === $user->id;

        abort_unless(
            $isSameBranch && ($isClassTrainer || $isCreator),
            403,
            'Anda tidak memiliki otoritas atas paket kuis kelas ini.'
        );
    }
}
