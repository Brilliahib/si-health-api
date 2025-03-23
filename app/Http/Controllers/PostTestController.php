<?php

namespace App\Http\Controllers;

use App\Models\PostTest;
use Illuminate\Http\Request;

class PostTestController extends Controller
{
    public function index()
    {
        $postTests = PostTest::all();

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

        $postTest->questionSet->questions->transform(function ($question) {
            if ($question->type !== 'multiple_choice') {
                unset($question->options);
            }
            return $question;
        });

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'PostTest fetched successfully',
                'statusCode' => 200,
            ],
            'data' => $postTest,
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
