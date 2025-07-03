<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'category.id' => 'required|integer',
            'category.name' => 'required|string',
            'name' => 'required|string',
            'photoUrls' => 'required|array',
            'photoUrls.*' => 'string',
            'tags' => 'required|array',
            'tags.*.id' => 'required|integer',
            'tags.*.name' => 'required|string',
            'status' => 'required|in:available,pending,sold',
        ]);

        // You can replace this with actual DB logic if needed
        return response()->json([
            'code' => 0,
            'type' => 'success',
            'message' => 'Pet added successfully',
            'data' => $validated,
        ]);
    }

    public function uploadImage($petId, Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif',
            'additionalMetadata' => 'nullable|string',
        ]);

        // Store the file
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store("pets/{$petId}", 'public');
        } else {
            return response()->json([
                'code' => 1,
                'type' => 'error',
                'message' => 'No file uploaded.',
            ], 400);
        }

        return response()->json([
            'code' => 0,
            'type' => 'success',
            'message' => "Image uploaded successfully to path: $path",
        ]);
    }
}
