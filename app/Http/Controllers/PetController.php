<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\FindPetsByStatusRequest;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Requests\UploadImagePetRequest;
use App\Models\Pet;
use App\Services\PetImageService;
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
        $pet = $this->petService->storeOrUpdatePet($data);

        return response()->json([
            'code' => 0,
            'type' => 'success',
            'message' => 'Pet added successfully',
            'data' => $data,
        ]);
    }

    public function show($petId)
    {
        $pet = Pet::with(['category', 'tags'])->findorFail($petId);
        return response()->json($pet, 200);
    }

    public function update(UpdatePetRequest $request, $id)
    {
        $pet = Pet::findorFail($id);
        $pet = $this->petService->storeOrUpdatePet($request->all(), $id);

        return response()->json([
            'code' => 200,
            'type' => 'success',
            'message' => 'Pet updated successfully',
            'data' => $pet
        ]);
    }

    public function destroy(Request $request, $petId)
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

        // Find the pet
        $pet = Pet::findorFail($petId);
        $pet->delete();

        return response()->json([
            'code' => 200,
            'type' => 'success',
            'message' => 'Pet deleted successfully'
        ]);
    }

    public function findByStatus(FindPetsByStatusRequest $request)
    {
        $pets = Pet::with(['category', 'tags']) // assumes relationships exist
                    ->whereIn('status', $request->statuses)
                    ->get();

        return response()->json($pets, 200);
    }

    public function uploadImage($petId, UploadImagePetRequest $request, PetImageService $petImageService)
    {
        $pet = $petImageService->uploadImage($petId, $request->file('file'));

        return response()->json([
            'code' => 0,
            'type' => 'success',
            'message' => "Image uploaded successfully",
            'data' => $pet
        ]);
    }

}
