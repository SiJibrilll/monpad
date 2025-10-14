<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\WeekType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeekTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_list_all_week_types()
    {
        $response = $this->getJson('/api/week-type');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'percentage'
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_week_type()
    {
        $weekType = WeekType::firstOrFail();

        $response = $this->getJson("/api/week-type/{$weekType->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $weekType->id,
                    'name' => $weekType->name,
                    'percentage' => $weekType->percentage,
                    
                ]
            ]);
    }

    /** @test */
    public function test_it_can_store_a_new_week_type()
    {
        $payload = [
            'name' => 'Ujian Triwulasan',
            'percentage' => 60,
        ];

        $response = $this->postJson('/api/week-type', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => $payload['name'],
                'percentage' => $payload['percentage'],
            ]);

        $this->assertDatabaseHas('week_types', ['name' => $payload['name'], 'percentage' => $payload['percentage']]);
    }

    /** @test */
    public function test_it_can_update_a_week_type()
    {
        $weekType = WeekType::first();

        $payload = [
            'name' => 'Ujian baratayuda',
            'percentage' => 60,
        ];

        $response = $this->putJson("/api/week-type/{$weekType->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $payload['name'],
                'percentage' => $payload['percentage'],
            ]);

        $this->assertDatabaseHas('week_types', ['id' => $weekType->id, 'name' => $payload['name'], 'percentage' => $payload['percentage']]);
    }

    /** @test */
    public function test_it_can_delete_a_week_type()
    {
        $weekType = WeekType::first();

        $response = $this->deleteJson("/api/week-type/{$weekType->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('week_types', ['id' => $weekType->id]);
    }
}
