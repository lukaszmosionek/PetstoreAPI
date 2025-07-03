<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Http\UploadedFile;

class PetImageService
{
    /**
     * Upload an image for the given pet ID.
     *
     * @param int $petId
     * @param UploadedFile $file
     * @return Pet
     */
    public function uploadImage(int $petId, UploadedFile $file): Pet
    {
        // Store the file in public disk under pets/{petId}
        $path = $file->store("pets/{$petId}", 'public');

        // Find the pet or fail
        $pet = Pet::findOrFail($petId);

        // Add the new photo URL
        $photoUrls = $pet->photoUrls ?? [];
        $photoUrls[] = $path;
        $pet->photoUrls = $photoUrls;

        // Save the pet model
        $pet->save();

        return $pet;
    }
}
