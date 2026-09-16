<?php

namespace App\Http\Controllers\Api\V1\Peserta;

use App\Http\Controllers\Controller;
use App\Http\Requests\Peserta\SubmitQuizRequest;
use App\Http\Resources\Peserta\QuizAttemptResource;
use App\Models\ActivityLog;
use App\Models\ClassEnrollment;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\TrainingClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizAttemptApiController extends Controller
{
    /**
     * Detail kuis & riwayat percobaan via API.
     */
    public function show(Request $request, TrainingClass $class, Quiz $quiz): JsonResponse
    {
        $user = $request->user();
        $this->authorizeQuizAccess($class, $quiz, $user->id);

        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->orderBy('attempt_number', 'desc')
            ->get();

        return response()->json([
            'quiz' => $quiz->load('questions.options'),
            'can_attempt' => $attempts->count() < $quiz->max_attempts,
            'attempts' => QuizAttemptResource::collection($attempts),
        ]);
    }

    /**
     * Mulai pengerjaan kuis baru via API.
     */
    public function start(Request $request, TrainingClass $class, Quiz $quiz): JsonResponse
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

        $ongoingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereNull('submitted_at')
            ->first();

        if (! $ongoingAttempt) {
            $ongoingAttempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => $user->id,
                'attempt_number' => $currentAttemptsCount + 1,
                'started_at' => now(),
                'total_score' => 0.00,
                'is_passed' => false,
            ]);
        }

        $questions = $quiz->is_randomized ? $quiz->questions()->with('options')->get()->shuffle() : $quiz->questions()->with('options')->get();

        return response()->json([
            'attempt' => new QuizAttemptResource($ongoingAttempt),
            'questions' => $questions,
            'time_limit_minutes' => $quiz->time_limit_minutes,
        ]);
    }

    /**
     * Submit jawaban kuis via API & auto-grading.
     */
    public function submit(SubmitQuizRequest $request, TrainingClass $class, Quiz $quiz, QuizAttempt $attempt): JsonResponse
    {
        $user = $request->user();
        $this->authorizeAttemptAccess($class, $quiz, $attempt, $user->id);

        if ($attempt->submitted_at) {
            return response()->json([
                'message' => 'Kuis ini sudah pernah dikirimkan sebelumnya.',
                'attempt' => new QuizAttemptResource($attempt->load('answers')),
            ], 422);
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
                        $scoreEarned = 1.00;
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
            description: "API: Peserta {$user->name} menyelesaikan kuis '{$quiz->title}' skor: {$attempt->fresh()->total_score}",
            target: $attempt,
            old: null,
            new: $attempt->fresh()->toArray(),
            branchId: $class->branch_id
        );

        return (new QuizAttemptResource($attempt->fresh()->load('answers')))
            ->response()
            ->setStatusCode(200);
    }

    protected function authorizeQuizAccess(TrainingClass $class, Quiz $quiz, int $userId): void
    {
        abort_unless($quiz->class_id === $class->id, 404, 'Kuis tidak ditemukan.');

        $isEnrolled = ClassEnrollment::where('class_id', $class->id)
            ->where('user_id', $userId)
            ->exists();

        abort_unless($isEnrolled, 403, 'Anda belum terdaftar pada kelas ini.');
    }

    protected function authorizeAttemptAccess(TrainingClass $class, Quiz $quiz, QuizAttempt $attempt, int $userId): void
    {
        $this->authorizeQuizAccess($class, $quiz, $userId);

        abort_unless(
            $attempt->quiz_id === $quiz->id && $attempt->user_id === $userId,
            403,
            'Anda tidak memiliki akses ke kuis ini.'
        );
    }
}
