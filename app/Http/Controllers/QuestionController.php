<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'question_set_id' => 'required|uuid|exists:question_sets,id',
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.score' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            $question = Question::create([
                'question_set_id' => $request->question_set_id,
                'question_text' => $request->question_text,
            ]);

            foreach ($request->options as $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['option_text'],
                    'score' => $opt['score'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'meta' => ['status' => 'success', 'message' => 'Question created'],
                'data' => $question->load('options'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'meta' => ['status' => 'error', 'message' => $th->getMessage()],
            ], 500);
        }
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json([
            'meta' => ['status' => 'success', 'message' => 'Question deleted'],
        ]);
    }

    public function show($id)
    {
        $question = Question::with('options')->findOrFail($id);

        return response()->json([
            'meta' => ['status' => 'success', 'message' => 'Question retrieved'],
            'data' => $question,
        ]);
    }
}
