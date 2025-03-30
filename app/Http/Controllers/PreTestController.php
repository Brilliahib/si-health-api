<?php

namespace App\Http\Controllers;

use App\Models\PreTest;
use App\Models\QuestionSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreTestController extends Controller
{
    public function index()
    {
        $preTests = PreTest::with('module')->get();

        return response()->json([
            'meta' => ['status' => 'success'],
            'data' => $preTests,
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
            $preTest = PreTest::create([
                'module_id' => $request->module_id,
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
