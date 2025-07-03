<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetController extends Controller
{
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
