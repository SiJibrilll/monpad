<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class DosenControllerTest extends TestCase
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
    public function test_it_can_list_all_dosen()
    {
        $response = $this->getJson('/api/dosen');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'username',
                        'email',
                        'nidn',
                        'fakultas',
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_dosen()
    {
        $user = User::dosen()->firstOrFail();

        $response = $this->getJson("/api/dosen/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'username' => $user->name,
                    'email' => $user->email,
                    'nidn' => $user->dosen_data->nidn,
                    'fakultas' => $user->dosen_data->fakultas,
                    
                ]
            ]);
    }

    /** @test */
    public function test_it_can_store_a_new_dosen()
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
    public function test_it_can_update_a_dosen()
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
    public function test_it_can_delete_a_dosen()
    {
        $user = User::dosen()->firstOrFail();

        $response = $this->deleteJson("/api/dosen/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('dosen_datas', ['user_id' => $user->id]);
    }
}
