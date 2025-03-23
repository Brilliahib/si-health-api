<?php

namespace App\Http\Controllers;

use App\Models\CAPD;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CAPDController extends Controller
{
    public function index()
    {
        $capds = CAPD::all();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'List of CAPDs retrieved successfully',
                'statusCode' => 200,
            ],
            'data' => $capds,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_id' => 'required|uuid',
            'video_url' => 'required|string',
            'name' => 'required|string',
            'content' => 'required|string',
        ]);

        $capd = CAPD::create([
            'id' => Str::uuid(),
            'module_id' => $request->module_id,
            'video_url' => $request->video_url,
            'name' => $request->name,
            'content' => $request->content,
        ]);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'CAPD created successfully',
                'statusCode' => 201,
            ],
            'data' => $capd,
        ], 201);
    }

    public function show($id)
    {
        $capd = CAPD::find($id);

        if (!$capd) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'CAPD not found',
                    'statusCode' => 404,
                ],
                'data' => null,
            ], 404);
        }

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'CAPD details retrieved successfully',
                'statusCode' => 200,
            ],
            'data' => $capd,
        ]);
    }

    public function update(Request $request, $id)
    {
        $capd = CAPD::find($id);

        if (!$capd) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'CAPD not found',
                    'statusCode' => 404,
                ],
                'data' => null,
            ], 404);
        }

        $capd->update($request->only(['video_url', 'content', 'module_id', 'name']));

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'CAPD updated successfully',
                'statusCode' => 200,
            ],
            'data' => $capd,
        ]);
    }

    public function destroy($id)
    {
        $capd = CAPD::find($id);

        if (!$capd) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'CAPD not found',
                    'statusCode' => 404,
                ],
                'data' => null,
            ], 404);
        }

        $capd->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'CAPD deleted successfully',
                'statusCode' => 200,
            ],
            'data' => null,
        ]);
    }
}
