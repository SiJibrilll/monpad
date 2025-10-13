<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_list_all_project()
    {
        $response = $this->getJson('/api/project');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nama_projek',
                        'owner' => [
                            'username',
                            'nidn'
                        ],
                        'asisten' => [
                            'username'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_project()
    {
        $project = Project::first();

        $response = $this->getJson("/api/project/{$project->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'nama_projek' => $project->nama_projek,
                    'semester' => $project->semester,
                    'owner' => [
                        'id' => $project->owner->id,
                        'username' => $project->owner->name,
                        'email' => $project->owner->email,
                    ]
                ]
            ], $strict=false);
    }

    /** @test */
    public function test_it_can_store_a_new_project()
    {
        $user = User::find(1);
        $asisten = User::find(2);

        // Fake-authenticate this user as Sanctum user with abilities
        Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])

        

        $payload = [
            'nama_projek'  => 'Sistem bank',
            'deskripsi'    => 'Aplikasi untuk mengelola data bank, anggota, dan peminjaman.',
            'semester'     => 5,
            'tahun_ajaran' => 2025,
            'asisten_id' => $asisten->id
        ];


        $this->postJson('/api/project', $payload)
             ->assertStatus(201)
             ->assertJson([
                'data' => [
                    'nama_projek' => 'Sistem bank',
                    'owner' => [
                        'id' => $user->id
                    ],
                    'asisten' => [
                        'id' => $asisten->id
                    ]
                ]
                
             ], $strict=false);

        // optional: check user is authenticated
        $this->assertAuthenticatedAs($user);

        

        $this->assertDatabaseHas('projects', ['nama_projek' => 'Sistem bank']);
    }

    /** @test */
    public function test_it_can_update_a_project()
    {
        $user = User::find(1);
        $project = $user->projects()->first();

        // Fake-authenticate this user as Sanctum user with abilities
        Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])

        $payload = [
            'nama_projek'  => 'new Title',
            'deskripsi'    => 'Wleee',
        ];


        $this->putJson('/api/project/' . $project->id, $payload)
             ->assertStatus(200)
             ->assertJson([
                'data' => [
                    'nama_projek' => 'new Title',
                    'deskripsi'    => 'Wleee',
                    'owner' => [
                        'id' => $user->id
                    ]
                ]
                
             ], $strict=false);

        // optional: check user is authenticated
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('projects', ['nama_projek' => 'new Title']);
    }

    /** @test */
    public function test_it_can_delete_a_project()
    {
        $user = User::find(1);
        $project = $user->projects()->first();

        // Fake-authenticate this user as Sanctum user with abilities
        Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])


        $response = $this->deleteJson("/api/dosen/{$project->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        
    }
}
