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

        $set->questions->transform(function ($question) {
            if ($question->type === 'essay') {
                unset($question->options);
            }
            return $question;
        });

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Question Set fetched successfully',
                'statusCode' => 200,
            ],
            'data' => $set,
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
