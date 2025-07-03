<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function createOrUpdate(array $categoryData): Category
    {
        return Category::updateOrCreate(
            ['id' => $categoryData['id']],
            ['name' => $categoryData['name']]
        );
    }
}
