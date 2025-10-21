<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Week;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeekControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_list_all_weeks()
    {
        $response = $this->getJson('/api/week');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'notes',
                        'grades' => [
                            "*" => [
                                'grade',
                                'grade_type' => [
                                    'name',
                                    'percentage'
                                ]
                            ]
                        ],
                        'week_type' => [
                            'name',
                            'percentage'
                        ],
                        'grader' => [
                            'username',
                            'email'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_week()
    {
        $week = Week::first();

        $response = $this->getJson("/api/week/{$week->id}");

        $grade = $week->grades()->first();
        $grader = $week->grader;

        $response->assertJsonFragment([
            'username' => $grader->name,
            'email' => $grader->email,
            'notes' => $week->notes,
            'name' => $grade->gradeType->name,
            'grade' => $grade->grade
        ]);


        
    }

    /** @test */
    public function it_can_store_a_new_dosen()
    {
        $payload = [
            'name' => 'Test Dosen',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nidn' => '987654321',
            'fakultas' => 'jawa',
        ];

        $response = $this->postJson('/api/dosen', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'username' => 'Test Dosen',
                'email' => 'test@example.com',
                'nidn' => '987654321',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('dosen_datas', ['nidn' => '987654321']);
    }

    /** @test */
    public function it_can_update_a_dosen()
    {
        $user = User::dosen()->firstOrFail();

        $payload = [
            'name' => 'Updated Name',
            'fakultas' => 'Inggris',
        ];

        $response = $this->putJson("/api/dosen/{$user->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'Updated Name',
                'fakultas' => 'Inggris',
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
        $this->assertDatabaseHas('dosen_datas', ['user_id' => $user->id, 'fakultas' => 'Inggris']);
    }

    /** @test */
    public function it_can_delete_a_dosen()
    {
        $user = User::dosen()->firstOrFail();

        $response = $this->deleteJson("/api/dosen/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('dosen_datas', ['user_id' => $user->id]);
    }
}
