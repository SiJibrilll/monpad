<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Project;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_list_all_groups()
    {
        $response = $this->getJson('/api/group');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nama',
                        'anggota' => [
                            '*' => [
                                'id',
                                'username'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_group()
    {
        $group = Group::first();

        $response = $this->getJson("/api/group/{$group->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nama',
                    'anggota' => [
                        '*' => [
                            'id',
                            'username'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_store_a_new_group()
    {
        $project = Project::first();

        $payload = [
            'name' => 'Kelompok mawar',
            'project_id' => $project->id
        ];

        $response = $this->postJson('/api/group', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'nama' => 'Kelompok mawar'
            ]);

        $this->assertDatabaseHas('groups', ['name' => 'Kelompok mawar', 'project_id' => $project->id]);
    }

    /** @test */
    public function test_it_can_update_a_group()
    {
        $newProj = Project::create([
            'nama_projek' => 'sistem bom',
            'deskripsi' => 'bapak mau ngeledakin gedung',
            'asisten_id' => 2,
            'semester' => 4,
            'tahun_ajaran' => 2021,
            'user_id' => 1
        ]);

        $group = Group::first();

        $payload = [
            'name' => 'Kelompok anggrek',
            'project_id' => $newProj->id
        ];

        $response = $this->putJson("/api/group/{$group->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'nama' => 'Kelompok anggrek',
                'project_id' => $newProj->id
            ]);

        $this->assertDatabaseHas('groups', ['id' => $group->id, 'name' => 'Kelompok anggrek', 'project_id' => $newProj->id]);
    }

    /** @test */
    public function test_it_can_delete_a_group()
    {
        $group = Group::firstOrFail();

        $response = $this->deleteJson("/api/group/{$group->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
