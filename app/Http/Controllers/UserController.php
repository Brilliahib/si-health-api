<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Daftar user berhasil diambil',
                'statusCode' => 200
            ],
            'data' => User::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'User berhasil dibuat',
                'statusCode' => 201
            ],
            'data' => $user
        ], 201);
    }

    public function getMedicalPersonals()
    {
        $users = User::where('role', 'medical_personal')->get();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Daftar user dengan role medical_personal berhasil diambil',
                'statusCode' => 200
            ],
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::with('historyScreening.screening')->find($id);

        if (!$user) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'User tidak ditemukan',
                    'statusCode' => 404
                ],
                'data' => null
            ], 404);
        }

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'User ditemukan',
                'statusCode' => 200
            ],
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'User tidak ditemukan',
                    'statusCode' => 404
                ],
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
        ]);

        $user->update($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'User berhasil diperbarui',
                'statusCode' => 200
            ],
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'User tidak ditemukan',
                    'statusCode' => 404
                ],
                'data' => null
            ], 404);
        }

        $user->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'User berhasil dihapus',
                'statusCode' => 200
            ],
            'data' => null
        ]);
    }
}
