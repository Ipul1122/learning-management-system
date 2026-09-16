<?php

namespace App\Http\Controllers\Api\V1\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\StoreQuizRequest;
use App\Http\Requests\Trainer\UpdateQuizRequest;
use App\Http\Resources\Trainer\QuizResource;
use App\Models\ActivityLog;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class QuizApiController extends Controller
{
    /**
     * Tampilkan daftar kuis pada kelas yang diampu trainer via API.
     */
    public function index(Request $request): AnonymousResourceCollection
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

        $perPage = min((int) $request->input('per_page', 20), 100);
        $quizzes = $query->latest()->paginate($perPage)->withQueryString();

        return QuizResource::collection($quizzes);
    }

    /**
     * Buat kuis baru via API.
     */
    public function store(StoreQuizRequest $request): JsonResponse
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
            description: "API: Membuat kuis '{$quiz->title}' untuk kelas #{$quiz->class_id}",
            target: $quiz,
            old: null,
            new: $quiz->load('questions')->toArray(),
            branchId: $branch->id
        );

        return (new QuizResource($quiz->load(['class', 'creator', 'questions.options'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tampilkan detail satu kuis via API.
     */
    public function show(Request $request, Quiz $quiz): QuizResource
    {
        $this->authorizeQuizAccess($quiz, $request->user());

        return new QuizResource($quiz->load(['class', 'creator', 'questions.options']));
    }

    /**
     * Perbarui konfigurasi kuis via API.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz): QuizResource
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
            description: "API: Memperbarui kuis '{$quiz->title}'",
            target: $quiz,
            old: $oldData,
            new: $newData,
            branchId: $branch->id
        );

        return new QuizResource($quiz->fresh()->load(['class', 'creator', 'questions.options']));
    }

    /**
     * Hapus kuis via API.
     */
    public function destroy(Request $request, Quiz $quiz): JsonResponse
    {
        $user = $request->user();
        $this->authorizeQuizAccess($quiz, $user);
        $branch = $user->branch;

        $oldData = $quiz->toArray();
        $quizTitle = $quiz->title;
        $quiz->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "API: Menghapus kuis '{$quizTitle}'",
            target: null,
            old: $oldData,
            new: null,
            branchId: $branch->id
        );

        return response()->json([
            'message' => 'Paket kuis berhasil dihapus.',
        ]);
    }

    /**
     * Verifikasi wewenang trainer terhadap kuis.
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
