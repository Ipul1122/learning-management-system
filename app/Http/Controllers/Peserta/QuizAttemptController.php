<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Http\Requests\Peserta\SubmitQuizRequest;
use App\Models\ActivityLog;
use App\Models\ClassEnrollment;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\TrainingClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuizAttemptController extends Controller
{
    /**
     * Tampilkan briefing kuis & riwayat percobaan pengerjaan peserta.
     */
    public function show(Request $request, TrainingClass $class, Quiz $quiz): View|RedirectResponse
    {
        $user = $request->user();
        $this->authorizeQuizAccess($class, $quiz, $user->id);

        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->orderBy('attempt_number', 'desc')
            ->get();

        $canAttempt = $attempts->count() < $quiz->max_attempts;
        $bestScore = $attempts->max('total_score');
        $hasPassed = $attempts->contains('is_passed', true);

        return view('peserta.quizzes.show', compact('class', 'quiz', 'attempts', 'canAttempt', 'bestScore', 'hasPassed'));
    }

    /**
     * Inisiasi percobaan pengerjaan kuis baru.
     */
    public function start(Request $request, TrainingClass $class, Quiz $quiz): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeQuizAccess($class, $quiz, $user->id);

        $currentAttemptsCount = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->count();

        abort_if(
            $currentAttemptsCount >= $quiz->max_attempts,
            403,
            'Anda telah mencapai batas maksimal percobaan pengerjaan untuk kuis ini.'
        );

        // Cek apakah ada attempt yang masih berjalan (belum disubmit)
        $ongoingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereNull('submitted_at')
            ->first();

        if ($ongoingAttempt) {
            return redirect()->route('peserta.quizzes.take', [$class, $quiz, $ongoingAttempt]);
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'attempt_number' => $currentAttemptsCount + 1,
            'started_at' => now(),
            'total_score' => 0.00,
            'is_passed' => false,
        ]);

        return redirect()->route('peserta.quizzes.take', [$class, $quiz, $attempt]);
    }

    /**
     * Antarmuka pengerjaan kuis interaktif dengan timer countdown mundur.
     */
    public function take(Request $request, TrainingClass $class, Quiz $quiz, QuizAttempt $attempt): View|RedirectResponse
    {
        $user = $request->user();
        $this->authorizeAttemptAccess($class, $quiz, $attempt, $user->id);

        if ($attempt->submitted_at) {
            return redirect()->route('peserta.quizzes.result', [$class, $quiz, $attempt]);
        }

        // Ambil butir soal
        $questionsQuery = $quiz->questions()->with('options');
        $questions = $quiz->is_randomized ? $questionsQuery->get()->shuffle() : $questionsQuery->get();

        // Hitung sisa waktu pengerjaan dalam detik (dibulatkan bulat)
        $deadline = $attempt->started_at->addMinutes($quiz->time_limit_minutes);
        $secondsRemaining = (int) max(0, round(now()->diffInSeconds($deadline, false)));

        return view('peserta.quizzes.take', compact('class', 'quiz', 'attempt', 'questions', 'secondsRemaining'));
    }

    /**
     * Simpan jawaban peserta dan lakukan penilaian otomatis instan (Auto-grading PG & T/F).
     */
    public function submit(SubmitQuizRequest $request, TrainingClass $class, Quiz $quiz, QuizAttempt $attempt): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeAttemptAccess($class, $quiz, $attempt, $user->id);

        if ($attempt->submitted_at) {
            return redirect()->route('peserta.quizzes.result', [$class, $quiz, $attempt]);
        }

        $inputAnswers = $request->input('answers', []);
        $totalQuestions = $quiz->questions()->count();

        DB::transaction(function () use ($attempt, $inputAnswers, $quiz, $totalQuestions) {
            $correctAnswersCount = 0;

            foreach ($inputAnswers as $ans) {
                $questionId = $ans['question_id'] ?? null;
                $optionId = $ans['selected_option_id'] ?? null;
                $essayText = $ans['essay_answer'] ?? null;

                $isCorrect = false;
                $scoreEarned = 0.00;

                if ($optionId) {
                    $selectedOption = QuestionOption::where('id', $optionId)
                        ->where('question_id', $questionId)
                        ->first();

                    if ($selectedOption && $selectedOption->is_correct) {
                        $isCorrect = true;
                        $scoreEarned = 1.00; // Bobot setara (1 poin)
                        $correctAnswersCount++;
                    }
                }

                QuizAttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $questionId,
                    'selected_option_id' => $optionId,
                    'essay_answer' => $essayText,
                    'is_correct' => $isCorrect,
                    'score_earned' => $scoreEarned,
                ]);
            }

            // Hitung nilai akhir proporsional skala 0 s/d 100
            $finalScore = $totalQuestions > 0 ? round(($correctAnswersCount / $totalQuestions) * 100, 2) : 0.00;
            $isPassed = $finalScore >= (float) $quiz->passing_grade;

            $attempt->update([
                'submitted_at' => now(),
                'total_score' => $finalScore,
                'is_passed' => $isPassed,
            ]);
        });

        ActivityLog::record(
            action: 'SUBMIT_QUIZ',
            description: "Peserta {$user->name} menyelesaikan kuis '{$quiz->title}' dengan skor {$attempt->fresh()->total_score} (Status: ".($attempt->fresh()->is_passed ? 'Lulus' : 'Belum Lulus').')',
            target: $attempt,
            old: null,
            new: $attempt->fresh()->toArray(),
            branchId: $class->branch_id
        );

        // Gamifikasi: Perolehan XP dari nilai kuis & Cek Lencana Akurasi Kuis
        $earnedXp = max(10, (int) round(($attempt->fresh()->total_score ?? 0) / 2));
        app(\App\Services\GamificationService::class)->awardPoints(
            $user,
            $earnedXp,
            'quiz',
            "Penyelesaian Kuis '{$quiz->title}' (Skor: {$attempt->fresh()->total_score})",
            $attempt->id
        );

        return redirect()
            ->route('peserta.quizzes.result', [$class, $quiz, $attempt])
            ->with('success', 'Jawaban kuis Anda berhasil dikirimkan dan dievaluasi!');
    }

    /**
     * Tampilan lembar hasil nilai kuis dan pembahasan kunci jawaban.
     */
    public function result(Request $request, TrainingClass $class, Quiz $quiz, QuizAttempt $attempt): View
    {
        $user = $request->user();
        $this->authorizeAttemptAccess($class, $quiz, $attempt, $user->id);

        $attempt->load(['answers.question.options', 'answers.selectedOption']);

        return view('peserta.quizzes.result', compact('class', 'quiz', 'attempt'));
    }

    protected function authorizeQuizAccess(TrainingClass $class, Quiz $quiz, int $userId): void
    {
        abort_unless(
            $quiz->class_id === $class->id,
            404,
            'Kuis tidak ditemukan pada kelas pelatihan ini.'
        );

        $isEnrolled = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $userId)
            ->exists();

        abort_unless(
            $isEnrolled,
            403,
            'Anda harus terdaftar pada kelas ini untuk mengakses paket kuis.'
        );
    }

    protected function authorizeAttemptAccess(TrainingClass $class, Quiz $quiz, QuizAttempt $attempt, int $userId): void
    {
        $this->authorizeQuizAccess($class, $quiz, $userId);

        abort_unless(
            $attempt->quiz_id === $quiz->id && $attempt->user_id === $userId,
            403,
            'Anda tidak memiliki akses ke lembar pengerjaan kuis ini.'
        );
    }
}
