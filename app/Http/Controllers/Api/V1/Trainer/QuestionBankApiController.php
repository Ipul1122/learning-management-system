<?php

namespace App\Http\Controllers\Api\V1\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\StoreQuestionRequest;
use App\Http\Requests\Trainer\UpdateQuestionRequest;
use App\Http\Resources\Trainer\QuestionResource;
use App\Models\ActivityLog;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class QuestionBankApiController extends Controller
{
    /**
     * Tampilkan daftar butir soal cabang via API.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $branch = $user->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        $query = Question::where('branch_id', $branch->id)
            ->with(['creator', 'options']);

        if ($request->filled('type')) {
            $query->where('question_type', $request->input('type'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('question_text', 'like', "%{$search}%");
        }

        $perPage = min((int) $request->input('per_page', 20), 100);
        $questions = $query->latest()->paginate($perPage);

        return QuestionResource::collection($questions);
    }

    /**
     * Tambahkan butir soal baru via API.
     */
    public function store(StoreQuestionRequest $request): JsonResponse
    {
        $user = $request->user();
        $branch = $user->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        $question = DB::transaction(function () use ($request, $user, $branch) {
            $question = Question::create([
                'branch_id' => $branch->id,
                'creator_id' => $user->id,
                'question_text' => $request->input('question_text'),
                'question_type' => $request->input('question_type'),
                'score_weight' => 1,
                'explanation' => $request->input('explanation'),
            ]);

            if (in_array($question->question_type, ['multiple_choice', 'true_false'])) {
                $options = $request->input('options', []);
                foreach ($options as $index => $opt) {
                    if (! empty($opt['option_text'])) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $opt['option_text'],
                            'is_correct' => ! empty($opt['is_correct']),
                            'option_order' => $index + 1,
                        ]);
                    }
                }
            }

            return $question;
        });

        ActivityLog::record(
            action: 'CREATE',
            description: "API: Menambahkan butir soal tipe {$question->question_type} pada bank soal cabang {$branch->name}",
            target: $question,
            old: null,
            new: $question->load('options')->toArray(),
            branchId: $branch->id
        );

        return (new QuestionResource($question->load(['creator', 'options'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tampilkan detail satu butir soal via API.
     */
    public function show(Request $request, Question $question): QuestionResource
    {
        $this->authorizeQuestionAccess($question, $request->user()->branch_id);

        return new QuestionResource($question->load(['creator', 'options']));
    }

    /**
     * Perbarui butir soal via API.
     */
    public function update(UpdateQuestionRequest $request, Question $question): QuestionResource
    {
        $branchId = $request->user()->branch_id;
        $this->authorizeQuestionAccess($question, $branchId);

        $oldData = $question->load('options')->toArray();

        DB::transaction(function () use ($request, $question) {
            $question->update([
                'question_text' => $request->input('question_text'),
                'question_type' => $request->input('question_type'),
                'score_weight' => 1,
                'explanation' => $request->input('explanation'),
            ]);

            $question->options()->delete();

            if (in_array($question->question_type, ['multiple_choice', 'true_false'])) {
                $options = $request->input('options', []);
                foreach ($options as $index => $opt) {
                    if (! empty($opt['option_text'])) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $opt['option_text'],
                            'is_correct' => ! empty($opt['is_correct']),
                            'option_order' => $index + 1,
                        ]);
                    }
                }
            }
        });

        $newData = $question->fresh()->load('options')->toArray();

        ActivityLog::record(
            action: 'UPDATE',
            description: "API: Memperbarui butir soal #{$question->id} pada bank soal",
            target: $question,
            old: $oldData,
            new: $newData,
            branchId: $branchId
        );

        return new QuestionResource($question->fresh()->load(['creator', 'options']));
    }

    /**
     * Hapus butir soal via API.
     */
    public function destroy(Request $request, Question $question): JsonResponse
    {
        $branchId = $request->user()->branch_id;
        $this->authorizeQuestionAccess($question, $branchId);

        $oldData = $question->load('options')->toArray();
        $questionId = $question->id;
        $question->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "API: Menghapus butir soal #{$questionId} dari bank soal",
            target: null,
            old: $oldData,
            new: null,
            branchId: $branchId
        );

        return response()->json([
            'message' => 'Butir soal berhasil dihapus dari bank soal.',
        ]);
    }

    protected function authorizeQuestionAccess(Question $question, ?int $branchId): void
    {
        abort_unless(
            $branchId && $question->branch_id === $branchId,
            403,
            'Anda tidak memiliki wewenang mengakses bank soal cabang lain.'
        );
    }
}
