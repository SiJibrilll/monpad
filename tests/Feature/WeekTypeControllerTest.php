<?php

namespace Tests\Feature;

use App\Models\GradeType;
use Tests\TestCase;
use App\Models\User;
use App\Models\WeekType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class WeekTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
        
         $user = User::find(1);
        // Fake-authenticate this user as Sanctum user with abilities
        Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])
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
                        'percentage',
                        'grade_types' => [
                            "*" => [
                                'name',
                                'percentage'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_week_type()
    {
        $weekType = WeekType::firstOrFail();
        $gradeType = $weekType->gradeType()->first();

        $response = $this->getJson("/api/week-type/{$weekType->id}");


        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $weekType->id,
                    'name' => $weekType->name,
                    'percentage' => $weekType->percentage,
                ]
            ]);
        
            $response->assertJsonFragment([
                'id' => $gradeType->id
            ]);
    }

    /** @test */
    public function test_it_can_store_a_new_week_type()
    {
        $gradeType = GradeType::first();

        $payload = [
            'name' => 'Ujian Triwulasan',
            'percentage' => 60,
            'grade_types' => [$gradeType->id]
        ];

        $response = $this->postJson('/api/week-type', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => $payload['name'],
                'percentage' => $payload['percentage'],
                'id' => $gradeType->id
            ]);

        $this->assertDatabaseHas('week_types', ['name' => $payload['name'], 'percentage' => $payload['percentage']]);
        $this->assertDatabaseHas('week_type_grade_type', ['grade_type_id' => $gradeType->id, 'week_type_id' => $response->json('data.id')]);
    }

    /** @test */
    public function test_it_can_update_a_week_type()
    {
        $weekType = WeekType::first();
        $gradeType = GradeType::skip(1)->take(1)->first();

        $payload = [
            'name' => 'Ujian baratayuda',
            'percentage' => 60,
            'grade_types' => [$gradeType->id]
        ];

        $response = $this->putJson("/api/week-type/{$weekType->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $payload['name'],
                'percentage' => $payload['percentage'],
                'id' => $gradeType->id
            ]);

        $this->assertDatabaseHas('week_types', ['id' => $weekType->id, 'name' => $payload['name'], 'percentage' => $payload['percentage']]);
        $this->assertDatabaseHas('week_type_grade_type', ['grade_type_id' => $gradeType->id, 'week_type_id' => $response->json('data.id')]);
    }

    /** @test */
    public function test_it_can_delete_a_week_type()
    {
        $weekType = WeekType::first();

        $gradeType = $weekType->gradeType()->first();

        $response = $this->deleteJson("/api/week-type/{$weekType->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('week_types', ['id' => $weekType->id]);
        $this->assertDatabaseMissing('week_type_grade_type', ['week_type_id' => $weekType->id, 'grade_type_id' => $gradeType->id]);

    }
}
