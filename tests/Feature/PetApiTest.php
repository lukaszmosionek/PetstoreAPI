<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pet;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PetApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed initial data
        $this->artisan('migrate:fresh');
        $this->seed(); // uses DatabaseSeeder
    }

    public function test_find_pets_by_status()
    {
        $response = $this->getJson('/api/pet/findByStatus?status=available');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'category', 'photoUrls', 'tags', 'status']
        ]);
    }

    public function test_get_pet_by_id()
    {
        $pet = Pet::factory()->create();

        $response = $this->getJson("/api/pet/{$pet->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $pet->id,
            'name' => $pet->name
        ]);
    }

    public function test_update_pet_with_form_data()
    {
        $pet = Pet::factory()->create(['name' => 'OldName', 'status' => 'available']);

        $response = $this->post("/api/pet/{$pet->id}", [
            'name' => 'UpdatedName',
            'status' => 'sold'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'name' => 'UpdatedName',
            'status' => 'sold'
        ]);
    }

    public function test_delete_pet()
    {
        $pet = Pet::factory()->create();

        $response = $this->delete("/api/pet/{$pet->id}", [], [
            'api_key' => 'your_expected_api_key' // change if needed
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }

    public function test_invalid_id_handling()
    {
        $response = $this->get('/api/pet/999999'); // likely doesn't exist
        $response->assertStatus(404);
    }
}
