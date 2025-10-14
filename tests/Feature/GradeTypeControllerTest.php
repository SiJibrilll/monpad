<?php

namespace Tests\Feature;

use App\Models\GradeType;
use Tests\TestCase;
use App\Models\User;
use App\Models\WeekType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradeTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_list_all_grade_types()
    {
        $response = $this->getJson('/api/grade-type');

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
    public function test_it_can_show_a_single_grade_type()
    {
        $gradeType = GradeType::firstOrFail();

        $response = $this->getJson("/api/grade-type/{$gradeType->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $gradeType->id,
                    'name' => $gradeType->name,
                    'percentage' => $gradeType->percentage,
                    
                ]
            ]);
    }

    /** @test */
    public function test_it_can_store_a_new_week_type()
    {
        $payload = [
            'name' => 'Kerapihan',
            'percentage' => 60,
        ];

        $response = $this->postJson('/api/grade-type', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => $payload['name'],
                'percentage' => $payload['percentage'],
            ]);

        $this->assertDatabaseHas('grade_types', ['name' => $payload['name'], 'percentage' => $payload['percentage']]);
    }

    /** @test */
    public function test_it_can_update_a_grade_type()
    {
        $gradeType = GradeType::first();

        $payload = [
            'name' => 'Kesaktian',
            'percentage' => 60,
        ];

        $response = $this->putJson("/api/grade-type/{$gradeType->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $payload['name'],
                'percentage' => $payload['percentage'],
            ]);

        $this->assertDatabaseHas('grade_types', ['id' => $gradeType->id, 'name' => $payload['name'], 'percentage' => $payload['percentage']]);
    }

    /** @test */
    public function test_it_can_delete_a_grade_type()
    {
        $gradeType = GradeType::first();

        $response = $this->deleteJson("/api/grade-type/{$gradeType->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('grade_types', ['id' => $gradeType->id]);
    }
}
