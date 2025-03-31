<?php

namespace App\Http\Controllers;

use App\Models\UserHistoryPostTest;
use Illuminate\Http\Request;

class UserHistoryPostTestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $histories = UserHistoryPostTest::with('postTest')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Berhasil mengambil semua history post test',
            'data' => $histories,
        ]);
    }

    public function show($id)
    {
        $history = UserHistoryPostTest::with(['answer.question.options', 'answer.selectedOption'])
            ->where('id', $id)
            ->first();

        if (!$history) {
            return response()->json([
                'message' => 'History post test tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mengambil detail history post test',
            'data' => [
                'id' => $history->id,
                'sum_score' => $history->sum_score,
                'created_at' => $history->created_at,
                'answer' => $history->answer->map(function ($answer) {
                    return [
                        'id' => $answer->question->id,
                        'question' => $answer->question->question_text,
                        'options' => $answer->question->options->map(fn($opt) => [
                            'id' => $opt->id,
                            'text' => $opt->option_text,
                        ]),
                        'selected_option' => [
                            'id' => $answer->selectedOption?->id,
                            'text' => $answer->selectedOption?->option_text,
                        ],
                    ];
                }),
            ],
        ]);
    }
}
