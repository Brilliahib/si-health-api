<?php

namespace App\Http\Controllers;

use App\Models\UserHistoryScreening;

class UserHistoryScreeningController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $histories = UserHistoryScreening::with('screening')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Berhasil mengambil semua history screening',
            'data' => $histories,
        ]);
    }

    public function getAllHistory()
    {
        $histories = UserHistoryScreening::with(['postTest', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Berhasil mengambil semua history post test (semua user)',
            'data' => $histories,
        ]);
    }

    public function show($id)
    {

        $history = UserHistoryScreening::with(['answer.question.options', 'answer.selectedOption',])
            ->where('id', $id)
            ->first();

        if (!$history) {
            return response()->json([
                'message' => 'History screening tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mengambil detail history screening',
            'data' => [
                'id' => $history->id,
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
                'created_at' => $history->created_at,
            ],
        ]);
    }
}
