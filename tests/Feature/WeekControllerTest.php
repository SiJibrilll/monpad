<?php

namespace Tests\Feature;

use App\Models\GradeFinalization;
use App\Models\Project;
use Tests\TestCase;
use App\Models\User;
use App\Models\Week;
use App\Models\WeekType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class WeekControllerTest extends TestCase
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
    public function test_it_can_store_a_new_week()
    {
        $grader = User::asisten()->first();
        $project = Project::first();
        $weekType = WeekType::first();

        $payload = [
            'notes' => 'Kurang rapih',
            'grader_id' => $grader->id,
            'date' => now(),
            'week_type_id' => $weekType->id,
            'grades' => [
                ['grade_type_id' => 1, 'grade' => 100],
                ['grade_type_id' => 2, 'grade' => 50]
            ],
            'project_id' => $project->id,
            
        ];

        $response = $this->postJson('/api/week', $payload);
        
        $response->assertStatus(201)
            ->assertJsonFragment([
                'notes' => $payload['notes'],
                'username' => $grader->name,
                'grade' => 100,
                'name' => $weekType->name
            ]);

        $this->assertDatabaseHas('weeks', ['notes' => $payload['notes']]);
        $this->assertDatabaseHas('grades', ['grade_type_id' => $payload['grades'][0]['grade_type_id'], 'grade' => $payload['grades'][0]['grade']]);
    }

    /** @test */
    public function test_it_can_update_a_week_and_grade()
    {
        $week = Week::first();

        $grader = User::asisten()->first();
        $project = Project::first();
        $weekType = WeekType::first();

        $payload = [
            'notes' => 'Udah keren',
            'grader_id' => $grader->id,
            'date' => now(),
            'week_type_id' => $weekType->id,
            'grades' => [
                ['grade_type_id' => 1, 'grade' => 10],
                ['grade_type_id' => 2, 'grade' => 100]
            ],
            'project_id' => $project->id,
            
        ];

        $response = $this->putJson("/api/week/{$week->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'notes' => $payload['notes'],
                'username' => $grader->name,
                'grade' => 100,
                'name' => $weekType->name
            ]);

        $this->assertDatabaseHas('weeks', ['notes' => $payload['notes']]);
        $this->assertDatabaseHas('grades', ['grade_type_id' => $payload['grades'][0]['grade_type_id'], 'grade' => $payload['grades'][0]['grade']]);
    }

    /** @test */
    public function test_it_can_delete_a_week_and_grade()
    {
        $week = Week::firstOrFail();
        $grade = $week->grades()->first();

        $response = $this->deleteJson("/api/week/{$week->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('weeks', ['id' => $week->id]);
        $this->assertDatabaseMissing('grades', ['id' => $grade->id]);
    }

    /** @test */
    public function test_it_cannot_store_a_new_week_in_a_final_grade()
    {
        $grader = User::asisten()->first();
        $project = Project::first();
        $weekType = WeekType::first();

        $finalization = GradeFinalization::where('project_id', $project->id)->first();
        $this->postJson("/api/finalization/{$finalization->id}");

        $payload = [
            'notes' => 'Kurang rapih',
            'grader_id' => $grader->id,
            'date' => now(),
            'week_type_id' => $weekType->id,
            'grades' => [
                ['grade_type_id' => 1, 'grade' => 100],
                ['grade_type_id' => 2, 'grade' => 50]
            ],
            'project_id' => $project->id,
            
        ];

        $response = $this->postJson('/api/week', $payload);
    
        
        $response->assertStatus(403)
            ->assertJsonFragment([
                'message' => 'This record is finalized.'
            ]);

        $this->assertDatabaseMissing('weeks', ['notes' => $payload['notes']]);
    }

    /** @test */
    public function test_it_cannot_update_a_final_week_and_grade()
    {
        $week = Week::first();

        $grader = User::asisten()->first();
        $project = Project::first();
        $weekType = WeekType::first();

        $finalization = GradeFinalization::where('project_id', $project->id)->first();
        $this->postJson("/api/finalization/{$finalization->id}");

        $payload = [
            'notes' => 'Udah keren',
            'grader_id' => $grader->id,
            'date' => now(),
            'week_type_id' => $weekType->id,
            'grades' => [
                ['grade_type_id' => 1, 'grade' => 10],
                ['grade_type_id' => 2, 'grade' => 100]
            ],
            'project_id' => $project->id,
            
        ];

        $response = $this->putJson("/api/week/{$week->id}", $payload);

        $response->assertStatus(403)
            ->assertJsonFragment([
                'message' => 'This record is finalized.'
            ]);

        $this->assertDatabaseMissing('weeks', ['notes' => $payload['notes']]);
        $this->assertDatabaseMissing('grades', ['grade_type_id' => $payload['grades'][0]['grade_type_id'], 'grade' => $payload['grades'][0]['grade']]);
    }
}
