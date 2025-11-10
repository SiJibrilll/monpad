<?php

namespace Tests\Feature;

use App\Models\GradeFinalization;
use App\Models\GradeType;
use App\Models\Group;
use App\Models\Presence;
use Tests\TestCase;
use App\Models\User;
use App\Models\WeekType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class PresenceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();

        $user = User::find(2);
         
        // Fake-authenticate this user as Sanctum user with abilities
        Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])
    }

    /** @test */
    public function test_it_can_list_all_presence_of_a_group_in_a_week_type()
    {
        $group = Group::first();
        $weekType = WeekType::first();

        $response = $this->getJson("/api/group/{$group->id}/weekly-presence/{$weekType->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'presence_id',
                        'week_id',
                        'group_id',
                        'mahasiswa' => [
                            'username',
                            'nim'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_update_a_presence_for_a_group()
    {
        $group = Group::first();
        $weekType = WeekType::first();

        $this->getJson("/api/group/{$group->id}/weekly-presence/{$weekType->id}");
        
        $presences = Presence::where('group_id', $group->id)
        ->where('week_type_id', $weekType->id)
        ->get();

        $payload = [
            'presences' => []
        ];

        foreach ($presences as $presence) {
            $payload['presences'][] = ["presence_id" => $presence->id, 'present' => true];
        }

        $response = $this->putJson("/api/group/{$group->id}/weekly-presence/{$weekType->id}", $payload);

        $test = $payload['presences'][0];
        $response->assertStatus(200)
            ->assertJsonFragment([
                'present' => $test['present'],
            ]);

        $this->assertDatabaseHas('presences', ['id' => $test['presence_id'], 'present' => $test['present']]);
    }

    /** @test */
    public function test_it_cannot_update_a_presence_for_a_finalized_group()
    {
        $group = Group::first();
        $weekType = WeekType::first();
        $project = $group->project;

        $finalization = GradeFinalization::where('project_id', $project->id)->first();
        
        // fake auth as dosen
        $user = User::find(1);
        Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])
        $finalres = $this->postJson("/api/finalization/{$finalization->id}");

        // fake switch back to asisten
        $user = User::find(2);
        Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])

        $this->getJson("/api/group/{$group->id}/weekly-presence/{$weekType->id}");
        
        $presences = Presence::where('group_id', $group->id)
        ->where('week_type_id', $weekType->id)
        ->get();

        $payload = [
            'presences' => []
        ];

        foreach ($presences as $presence) {
            $payload['presences'][] = ["presence_id" => $presence->id, 'present' => true];
        }
        

        $response = $this->putJson("/api/group/{$group->id}/weekly-presence/{$weekType->id}", $payload);

        // we skip to 1 because user 0 has presences on seeder for finalize grade testing
        $test = $payload['presences'][1];
        $response->assertStatus(403)
            ->assertJsonFragment([
                'message' => 'This record is finalized.'
            ]);

        $this->assertDatabaseMissing('presences', ['id' => $test['presence_id'], 'present' => $test['present']]);
    }
}
