<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::all();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Successfully get all mdoules',
                'statusCode' => 200,
            ],
            'data' => $modules,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:hd,capd',
        ]);

        $module = Module::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
        ]);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Module created successfully',
                'statusCode' => 201,
            ],
            'data' => $module,
        ], 201);
    }

    public function show($id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'Module not found',
                    'statusCode' => 404,
                ],
                'data' => null,
            ], 404);
        }

        $details = match ($module->type) {
            'capd' => $module->capds,
            'hd' => $module->hds,
            default => [],
        };

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Module found',
                'statusCode' => 200,
            ],
            'data' => [
                'module' => $module->makeHidden(['capds', 'hds', 'preTests', 'postTests']),
                'pre_test' => $module->preTests,
                'sub_modules' => $details,
                'post_test' => $module->postTests,
            ],
        ]);
    }


    public function update(Request $request, $id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'Module not found',
                    'statusCode' => 404,
                ],
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:hd,capd',
        ]);

        $module->update($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Module updated successfully',
                'statusCode' => 200,
            ],
            'data' => $module,
        ]);
    }

    public function destroy($id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => 'Module not found',
                    'statusCode' => 404,
                ],
                'data' => null,
            ], 404);
        }

        $module->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Module deleted successfully',
                'statusCode' => 200,
            ],
            'data' => null,
        ]);
    }

    public function getByType(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:hd,capd',
        ]);

        $modules = Module::where('type', $validated['type'])->get();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'message' => 'Modul retrieved successfully',
                'statusCode' => 200,
            ],
            'data' => $modules,
        ]);
    }
}
