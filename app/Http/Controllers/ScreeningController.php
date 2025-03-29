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

        $screening->questionSet->questions->transform(function ($question) {
            if ($question->type !== 'multiple_choice') {
                unset($question->options);
            }
            return $question;
        });

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Screening fetched successfully',
                'statusCode' => 200,
            ],
            'data' => $screening,
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
