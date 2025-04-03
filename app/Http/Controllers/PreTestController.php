<?php

namespace App\Http\Controllers;

use App\Models\PreTest;
use Illuminate\Http\Request;

class PreTestController extends Controller
{
    public function index()
    {
        $preTests = PreTest::with('subModule')->get();

        return response()->json([
            'meta' => ['status' => 'success'],
            'data' => $preTests,
        ]);
    }

    public function getBySubModule($sub_module_id)
    {
        $preTests = PreTest::where('sub_module_id', $sub_module_id)->get();

        return response()->json([
            'meta' => ['status' => 'success'],
            'data' => $preTests,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_module_id' => 'required|exists:sub_modules,id',
            'question_set_id' => 'required|exists:question_sets,id',
            'name' => 'required|string|max:255',
        ]);

        try {
            $preTest = PreTest::create([
                'sub_module_id' => $request->sub_module_id,
                'question_set_id' => $request->question_set_id,
                'name' => $request->name,
            ]);

            return response()->json([
                'meta' => [
                    'status' => 'success',
                    'message' => 'PreTest created successfully',
                    'statusCode' => 201
                ],
                'data' => $preTest,
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
        $preTest = PreTest::with('questionSet.questions.options')->findOrFail($id);

        $questions = $preTest->questionSet->questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question_set_id' => $question->question_set_id,
                'question_text' => $question->question_text,
                'options' => $question->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'option_text' => $option->option_text,
                        'score' => $option->score,
                    ];
                }),
            ];
        });

        $data = [
            'id' => $preTest->id,
            'name' => $preTest->name,
            'questions' => $questions,
        ];

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'PreTest fetched successfully',
                'statusCode' => 200,
            ],
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $preTest = PreTest::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string',
        ]);

        $preTest->update($request->only('title'));

        return response()->json([
            'meta' => ['status' => 'success', 'message' => 'PreTest updated'],
            'data' => $preTest,
        ]);
    }

    public function destroy($id)
    {
        $preTest = PreTest::findOrFail($id);
        $preTest->delete();

        return response()->json([
            'meta' => ['status' => 'success', 'message' => 'PreTest deleted'],
        ]);
    }
}
