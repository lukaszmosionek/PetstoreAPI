<?php

namespace App\Services;

use App\Models\Pet;
use App\Services\CategoryService;
use App\Services\TagService;

class PetService
{
    protected CategoryService $categoryService;
    protected TagService $tagService;

    public function __construct(CategoryService $categoryService, TagService $tagService)
    {
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    public function storePet(array $data): Pet
    {
        $category = $this->categoryService->createOrUpdate($data['category']);

        $pet = new Pet();
        $pet->id = $data['id'];
        $pet->category()->associate($category);
        $pet->name = $data['name'];
        $pet->photoUrls = json_encode($data['photoUrls']);
        $pet->status = $data['status'];
        $pet->save();

        $tagIds = $this->tagService->syncTags($data['tags']);
        $pet->tags()->sync($tagIds);

        return $pet;
    }
}
