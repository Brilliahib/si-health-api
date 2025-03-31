<?php

namespace App\Http\Controllers;

use App\Models\PostTest;
use Illuminate\Http\Request;

class PostTestController extends Controller
{
    public function index()
    {
        $postTests = PostTest::with('module')->get();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'List of Post Tests fetched successfully',
                'statusCode' => 200,
            ],
            'data' => $postTests,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'question_set_id' => 'required|exists:question_sets,id',
            'name' => 'required|string|max:255',
        ]);

        try {
            $postTest = PostTest::create([
                'module_id' => $request->module_id,
                'question_set_id' => $request->question_set_id,
                'name' => $request->name,
            ]);

            return response()->json([
                'meta' => [
                    'status' => 'success',
                    'message' => 'Post test created successfully',
                    'statusCode' => 201
                ],
                'data' => $postTest,
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
        $postTest = PostTest::with('questionSet.questions.options')->findOrFail($id);

        $questions = $postTest->questionSet->questions->map(function ($question) {
            $transformed = [
                'id' => $question->id,
                'type' => $question->type,
                'question_text' => $question->question_text,
            ];

            if ($question->type === 'multiple_choice') {
                $transformed['options'] = $question->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'option_text' => $option->option_text,
                        'score' => $option->score,
                    ];
                });
            }

            return $transformed;
        });

        $data = [
            'id' => $postTest->id,
            'name' => $postTest->name,
            'questions' => $questions,
        ];

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'PostTest fetched successfully',
                'statusCode' => 200,
            ],
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $postTest = PostTest::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string',
        ]);

        $postTest->update($request->only('title'));

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'PostTest updated successfully',
                'statusCode' => 200,
            ],
            'data' => $postTest,
        ]);
    }

    public function destroy($id)
    {
        $postTest = PostTest::findOrFail($id);
        $postTest->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'PostTest deleted successfully',
                'statusCode' => 200,
            ],
        ]);
    }
}
