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
            'type' => 'required|in:multiple_choice,essay',
            'question_text' => 'required|string',
            'answer_key' => 'nullable|string',
            'options' => 'nullable|array', // required if multiple_choice
        ]);

        DB::beginTransaction();

        try {
            $question = Question::create([
                'question_set_id' => $request->question_set_id,
                'type' => $request->type,
                'question_text' => $request->question_text,
                'answer_key' => $request->answer_key,
            ]);

            // Jika pilihan ganda, simpan opsi-nya
            if ($request->type === 'multiple_choice' && is_array($request->options)) {
                foreach ($request->options as $opt) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $opt['option_text'],
                        'is_correct' => $opt['is_correct'] ?? false,
                    ]);
                }
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
}
