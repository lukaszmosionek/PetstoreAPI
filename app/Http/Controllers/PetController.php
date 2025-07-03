<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetRequest;
use App\Models\Pet;
use Illuminate\Http\Request;
use App\Services\PetService;

class PetController extends Controller
{
    protected PetService $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    public function store(StorePetRequest $request)
    {
        $data = $request->validated();
        $pet = $this->petService->storePet($data);

        return response()->json([
            'code' => 0,
            'type' => 'success',
            'message' => 'Pet added successfully',
            'data' => $data,
        ]);
    }

    public function show($petId)
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

    public function update(Request $request, $petId)
    {
        if (!is_numeric($petId) || $petId <= 0) {
            return response()->json([
                'code' => 405,
                'type' => 'error',
                'message' => 'Invalid pet ID'
            ], 405);
        }

        $pet = Pet::find($petId);

        if (!$pet) {
            return response()->json([
                'code' => 405,
                'type' => 'error',
                'message' => 'Pet not found'
            ], 405);
        }

        // Optional: validate input
        $request->validate([
            'name' => 'nullable|string',
            'status' => 'nullable|string|in:available,pending,sold'
        ]);

        // Update fields if present
        if ($request->has('name')) {
            $pet->name = $request->input('name');
        }

        if ($request->has('status')) {
            $pet->status = $request->input('status');
        }

        $pet->save();

        return response()->json([
            'code' => 200,
            'type' => 'success',
            'message' => 'Pet updated successfully',
            'data' => $pet
        ]);
    }

    public function delete(Request $request, $petId)
    {
        // Optional: Check API key
        $apiKey = $request->header('api_key');
        if ($apiKey !== 'your_expected_api_key') {
            return response()->json([
                'code' => 401,
                'type' => 'error',
                'message' => 'Unauthorized: Invalid API key'
            ], 401);
        }

        // Validate ID
        if (!is_numeric($petId) || $petId <= 0) {
            return response()->json([
                'code' => 400,
                'type' => 'error',
                'message' => 'Invalid ID supplied'
            ], 400);
        }

        // Find the pet
        $pet = Pet::find($petId);

        if (!$pet) {
            return response()->json([
                'code' => 404,
                'type' => 'error',
                'message' => 'Pet not found'
            ], 404);
        }

        $pet->delete();

        return response()->json([
            'code' => 200,
            'type' => 'success',
            'message' => 'Pet deleted successfully'
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

}
