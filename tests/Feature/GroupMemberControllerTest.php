<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Project;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupMemberControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
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

    }


    /** @test */
    public function test_it_can_detach_a_group_member()
    {
        $group = Group::firstOrFail();
        $member = $group->members()->first();

        $response = $this->deleteJson("/api/group/{$group->id}/members/{$member->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('group_members', ['user_id' => $member->id]);
    }
}
