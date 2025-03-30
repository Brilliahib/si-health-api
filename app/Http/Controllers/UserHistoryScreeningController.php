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
        $user = auth()->user();

        $history = UserHistoryScreening::with(['userAnswerScreenings', 'userAnswerScreenings.questionAnswer.options', 'userAnswerScreenings.selectedOption',])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$history) {
            return response()->json([
                'message' => 'History screening tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mengambil detail history screening',
            'data' => $history,
        ]);
    }
}
