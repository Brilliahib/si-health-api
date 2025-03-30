<?php

namespace App\Http\Controllers;

use App\Models\UserHistoryScreening;
use Illuminate\Http\Request;

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
                'created_at' => $history->created_at,
                'answer' => $history->answer->map(function ($answer) {
                    return [
                        'question' => $answer->question->question_text,
                        'options' => $answer->question->options->map(fn($opt) => [
                            'text' => $opt->option_text,
                            'is_correct' => $opt->is_correct,
                        ]),
                        'selected_option' => [
                            'text' => $answer->selectedOption?->option_text,
                            'is_correct' => $answer->selectedOption?->is_correct,
                        ],
                    ];
                }),
            ],
        ]);
    }
}
