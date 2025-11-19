<?php

namespace Tests\Feature;

use App\Models\GradeFinalization;
use App\Models\Group;
use App\Models\Project;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class QualificationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
        $user = User::find(3);
         
        // Fake-authenticate this user as Sanctum user with abilities
        Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])
    }

    /** @test */
    public function test_it_can_store_qualification()
    {

        $member = User::find(4);
        $group = Group::find(1);

        $payload = [
            'notes' => 'orangnya rajin',
            'grades' => [
                ['personal_grade_type_id' => 1, 'grade' => 100],
                ['personal_grade_type_id' => 2, 'grade' => 90],
            ]
        ];

        $response = $this->postJson("/api/group/{$group->id}/members/{$member->id}/qualification", $payload);

       $response->assertStatus(200)
             ->assertJson(['message' => 'Qualification grading stored successfully!']);
    }

    /** @test */
    public function test_it_cannot_store_a_finalized_qualification()
    {
        $group = Group::find(1);
        $dosen = User::find(1);
        Sanctum::actingAs($dosen, ['*']); // or ->actingAs($user, ['create', 'update'])

        $project = $group->project;
        $finalization = GradeFinalization::where('project_id', $project->id)->first();
        $this->postJson("/api/finalization/{$finalization->id}");

        $penilai = User::find(3);
        Sanctum::actingAs($penilai, ['*']); // or ->actingAs($user, ['create', 'update'])
        $member = User::find(4);
        

        $payload = [
            'notes' => 'orangnya rajin',
            'grades' => [
                ['personal_grade_type_id' => 1, 'grade' => 100],
                ['personal_grade_type_id' => 2, 'grade' => 90],
            ]
        ];

        $response = $this->postJson("/api/group/{$group->id}/members/{$member->id}/qualification", $payload);

       $response->assertStatus(403)
            ->assertJsonFragment([
                'message' => 'This record is finalized.'
            ]);
             
    }
}
