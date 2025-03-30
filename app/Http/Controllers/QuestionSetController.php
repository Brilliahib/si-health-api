<?php

namespace App\Http\Controllers;

use App\Models\QuestionSet;
use Illuminate\Http\Request;

class QuestionSetController extends Controller
{
    public function index()
    {
        $sets = QuestionSet::all();

        return response()->json([
            'meta' => ['status' => 'success'],
            'data' => $sets,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $set = QuestionSet::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'meta' => ['status' => 'success', 'message' => 'QuestionSet created'],
            'data' => $set,
        ]);
    }

    public function show($id)
    {
        $set = QuestionSet::with(['questions.options'])->findOrFail($id);

        $questions = $set->questions->map(function ($question) {
            return [
                'id' => $question->id,
                'type' => $question->type,
                'question_text' => $question->question_text,
                'answer_key' => $question->answer_key,
                'options' => $question->type === 'multiple_choice'
                    ? $question->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'option_text' => $option->option_text,
                        ];
                    })
                    : [],
            ];
        });

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Question Set fetched successfully',
                'statusCode' => 200,
            ],
            'data' => [
                'id' => $set->id,
                'name' => $set->name,
                'questions' => $questions,
            ],
        ]);
    }

    public function destroy($id)
    {
        $set = QuestionSet::findOrFail($id);
        $set->delete();

        return response()->json([
            'meta' => ['status' => 'success', 'message' => 'QuestionSet deleted'],
        ]);
    }
}
