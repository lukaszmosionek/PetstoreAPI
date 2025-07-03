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

    public function findByStatus(Request $request)
    {
        $allowedStatuses = ['available', 'pending', 'sold'];

        $statuses = explode(',', $request->query('status'));

        // Validate status values
        foreach ($statuses as $status) {
            if (!in_array($status, $allowedStatuses)) {
                return response()->json([
                    'code' => 400,
                    'type' => 'error',
                    'message' => 'Invalid status value: ' . $status
                ], 400);
            }
        }

        $pets = Pet::with(['category', 'tags']) // assumes relationships exist
                    ->whereIn('status', $statuses)
                    ->get();

        return response()->json($pets, 200);
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

    public function findPetById($petId)
    {
        // Validate ID
        if (!is_numeric($petId) || $petId <= 0) {
            return response()->json([
                'code' => 400,
                'type' => 'error',
                'message' => 'Invalid ID supplied'
            ], 400);
        }

        // Fetch pet with relationships
        $pet = Pet::with(['category', 'tags'])->find($petId);

        if (!$pet) {
            return response()->json([
                'code' => 404,
                'type' => 'error',
                'message' => 'Pet not found'
            ], 404);
        }

        return response()->json($pet, 200);
    }
}
