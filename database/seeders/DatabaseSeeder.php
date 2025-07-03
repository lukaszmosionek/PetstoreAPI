<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Pet;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Categories
        $categories = Category::factory(5)->create();

        // Create Tags
        $tags = Tag::factory(10)->create();

        // Create Pets
        Pet::factory(20)->create()->each(function ($pet) use ($tags) {
            // Attach random tags
            $pet->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        });
    }
}
