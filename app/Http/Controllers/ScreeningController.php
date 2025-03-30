<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    public function index()
    {
        $screening = Screening::all();

        return response()->json([
            'meta' => ['status' => 'success'],
            'data' => $screening,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_set_id' => 'required|exists:question_sets,id',
            'name' => 'required|string|max:255',
        ]);

        try {
            $screening = Screening::create([
                'question_set_id' => $request->question_set_id,
                'name' => $request->name,
            ]);

            return response()->json([
                'meta' => [
                    'status' => 'success',
                    'message' => 'Screening created successfully',
                    'statusCode' => 201
                ],
                'data' => $screening,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => $th->getMessage(),
                    'statusCode' => 500
                ]
            ], 500);
        }
    }


    public function show($id)
    {
        $screening = Screening::with('questionSet.questions.options')->findOrFail($id);

        $questions = $screening->questionSet->questions->map(function ($question) {
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
                            'is_correct' => $option->is_correct,
                        ];
                    })
                    : [],
            ];
        });

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Screening fetched successfully',
                'statusCode' => 200,
            ],
            'data' => [
                'id' => $screening->id,
                'name' => $screening->name,
                'questions' => $questions,
            ],
        ]);
    }


    public function update(Request $request, $id)
    {
        $screening = Screening::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string',
        ]);

        $screening->update($request->only('title'));

        return response()->json([
            'meta' => ['status' => 'success', 'message' => 'Screening updated'],
            'data' => $screening,
        ]);
    }

    public function destroy($id)
    {
        $screening = Screening::findOrFail($id);
        $screening->delete();

        return response()->json([
            'meta' => ['status' => 'success', 'message' => 'Screening deleted'],
        ]);
    }
}
