<?php

namespace App\Http\Controllers;

use App\Models\UserAnswerPreTest;
use App\Models\UserHistoryPreTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserAnswerPreTestController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'pre_test_id' => 'required|uuid',
            'answers' => 'required|array|min:1',
            'answers.*.selected_option_id' => 'nullable|uuid|exists:options,id',
            'answers.*.question_id' => 'nullable|uuid|exists:questions,id',
            'answers.*.answer_text' => 'nullable|string',
        ]);

        $user = auth()->user();

        try {
            // 1. Save user history pre test
            $history = UserHistoryPreTest::create([
                'user_id' => $user->id,
                'pre_test_id' => $request->pre_test_id,
            ]);

            // 2. Save a all answer to user answer pre test
            foreach ($request->answers as $answer) {
                UserAnswerPreTest::create([
                    'user_id' => $user->id,
                    'user_history_pre_test_id' => $history->id,
                    'selected_option_id' => $answer['selected_option_id'] ?? null,
                    'question_id' => $answer['question_id'],
                    'answer_text' => $answer['answer_text'] ?? null,
                    'answered_at' => now(),
                ]);
            }

            return response()->json([
                'message' => 'Pre test submited successfully',
                'history_id' => $history->id,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Submit pre test error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
