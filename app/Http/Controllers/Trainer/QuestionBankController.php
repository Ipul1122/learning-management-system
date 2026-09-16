<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\StoreQuestionRequest;
use App\Http\Requests\Trainer\UpdateQuestionRequest;
use App\Models\ActivityLog;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuestionBankController extends Controller
{
    /**
     * Tampilkan daftar butir bank soal dalam cabang trainer.
     */
    public function index(Request $request): View
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

        $questions = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Question::where('branch_id', $branch->id)->count(),
            'multiple_choice' => Question::where('branch_id', $branch->id)->where('question_type', 'multiple_choice')->count(),
            'true_false' => Question::where('branch_id', $branch->id)->where('question_type', 'true_false')->count(),
            'essay' => Question::where('branch_id', $branch->id)->where('question_type', 'essay')->count(),
        ];

        return view('trainer.questions.index', compact('questions', 'stats', 'branch'));
    }

    /**
     * Tampilkan formulir pembuatan butir soal baru.
     */
    public function create(Request $request): View
    {
        $branch = $request->user()->branch;
        abort_unless($branch, 403, 'Akun Anda belum diasosiasikan dengan cabang.');

        return view('trainer.questions.create', compact('branch'));
    }

    /**
     * Simpan butir soal dan opsi jawaban ke database.
     */
    public function store(StoreQuestionRequest $request): RedirectResponse
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
                'score_weight' => 1, // Aturan bisnis PRD: Bobot setara/sama rata
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
            description: "Menambahkan butir soal tipe {$question->question_type} pada bank soal cabang {$branch->name}",
            target: $question,
            old: null,
            new: $question->load('options')->toArray(),
            branchId: $branch->id
        );

        return redirect()
            ->route('trainer.questions.index')
            ->with('success', 'Butir soal baru berhasil ditambahkan ke bank soal!');
    }

    /**
     * Tampilkan formulir edit butir soal.
     */
    public function edit(Request $request, Question $question): View
    {
        $branch = $request->user()->branch;
        $this->authorizeQuestionAccess($question, $branch?->id);

        $question->load('options');

        return view('trainer.questions.edit', compact('question', 'branch'));
    }

    /**
     * Perbarui butir soal dan opsi pilihan jawaban.
     */
    public function update(UpdateQuestionRequest $request, Question $question): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeQuestionAccess($question, $branch?->id);

        $oldData = $question->load('options')->toArray();

        DB::transaction(function () use ($request, $question) {
            $question->update([
                'question_text' => $request->input('question_text'),
                'question_type' => $request->input('question_type'),
                'score_weight' => 1, // Bobot setara
                'explanation' => $request->input('explanation'),
            ]);

            // Hapus opsi lama dan re-create
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
            description: "Memperbarui butir soal #{$question->id} pada bank soal cabang {$branch->name}",
            target: $question,
            old: $oldData,
            new: $newData,
            branchId: $branch->id
        );

        return redirect()
            ->route('trainer.questions.index')
            ->with('success', 'Butir soal berhasil diperbarui!');
    }

    /**
     * Hapus butir soal dari bank soal.
     */
    public function destroy(Request $request, Question $question): RedirectResponse
    {
        $branch = $request->user()->branch;
        $this->authorizeQuestionAccess($question, $branch?->id);

        $oldData = $question->load('options')->toArray();
        $questionId = $question->id;
        $question->delete();

        ActivityLog::record(
            action: 'DELETE',
            description: "Menghapus butir soal #{$questionId} dari bank soal cabang {$branch->name}",
            target: null,
            old: $oldData,
            new: null,
            branchId: $branch->id
        );

        return redirect()
            ->route('trainer.questions.index')
            ->with('success', 'Butir soal berhasil dihapus dari bank soal!');
    }

    protected function authorizeQuestionAccess(Question $question, ?int $branchId): void
    {
        abort_unless(
            $branchId && $question->branch_id === $branchId,
            403,
            'Anda tidak memiliki akses ke bank soal cabang lain.'
        );
    }
}
