<?php

namespace Tests\Feature;

use App\Models\GradeFinalization;
use App\Models\Group;
use App\Models\Mahasiswa;
use App\Models\Project;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class GroupMemberControllerTest extends TestCase
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
    public function test_it_can_list_all_members()
    {
        $group = Group::first();
        $response = $this->getJson("/api/group/{$group->id}/members");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'username',
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_assign_members_to_group()
    {
        $group = Group::first();

        $user1 = User::mahasiswa()->first();
        $user2 = User::mahasiswa()->skip(1)->take(1)->first();

        $payload = [
            'user_id' => [$user1->id, $user2->id]
        ];

        $response = $this->postJson("/api/group/{$group->id}/members", $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'username',
                    ]
                ]
            ]);

        $this->assertDatabaseHas('group_members', ['user_id' => $user1->id, 'group_id' => $group->id]);
        $this->assertDatabaseHas('group_members', ['user_id' => $user2->id, 'group_id' => $group->id]);
        $this->assertDatabaseHas('grade_finalizations', ['user_id' => $user2->id, 'project_id' => $group->project->id]);


    }


    /** @test */
    public function test_it_can_detach_a_group_member()
    {
        $group = Group::firstOrFail();
        $member = $group->members()->first();

        $response = $this->deleteJson("/api/group/{$group->id}/members/{$member->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('group_members', ['user_id' => $member->id]);
        $this->assertDatabaseMissing('grade_finalizations', ['user_id' => $member->id, 'project_id' => $group->project->id]);
    }

    /** @test */
    public function test_it_cannot_assign_members_to_finalized_group()
    {
        $group = Group::first();
        $project = $group->project;

        $finalization = GradeFinalization::where('project_id', $project->id)->first();
        $this->postJson("/api/finalization/{$finalization->id}");

        // create a new mahasiswa that will be inserted into a finalized group
        $user1 = Mahasiswa::createMahasiswa([
            'nim' => 'afffdsdfadf',
            'name' => 'fadfeafeae',
            'email' => 'ameil@mail.com',
            'jabatan' => 'BE',
            'angkatan' => 25,
            'password' => 'afaefaefa',
            'prodi' => 'TRPL'

        ]);

        $payload = [
            'user_id' => [$user1->id]
        ];

        $response = $this->postJson("/api/group/{$group->id}/members", $payload);

       $response->assertStatus(403)
            ->assertJsonFragment([
                'message' => 'This record is finalized.'
            ]);

        $this->assertDatabaseMissing('group_members', ['user_id' => $user1->id, 'group_id' => $group->id]);
        $this->assertDatabaseMissing('grade_finalizations', ['user_id' => $user1->id, 'project_id' => $group->project->id]);


    }


    /** @test */
    public function test_it_cannot_detach_a_finalized_group_member()
    {
        $group = Group::firstOrFail();
        $member = $group->members()->first();

        $project = $group->project;

        $finalization = GradeFinalization::where('project_id', $project->id)->first();
        $this->postJson("/api/finalization/{$finalization->id}");

        $response = $this->deleteJson("/api/group/{$group->id}/members/{$member->id}");

        $response->assertStatus(403)
            ->assertJsonFragment([
                'message' => 'This record is finalized.'
            ]);

        $this->assertDatabaseHas('group_members', ['user_id' => $member->id]);
        $this->assertDatabaseHas('grade_finalizations', ['user_id' => $member->id, 'project_id' => $group->project->id]);
    }
}
