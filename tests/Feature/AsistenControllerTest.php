<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AsistenControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_list_all_asisten()
    {
        $response = $this->getJson('/api/asisten');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'username',
                        'email',
                        'tahun_ajaran',
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_asisten()
    {
        $user = User::asisten()->firstOrFail();

        $response = $this->getJson("/api/asisten/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'username' => $user->name,
                    'email' => $user->email,
                    'tahun_ajaran' => $user->asisten_data->tahun_ajaran
                    
                ]
            ]);
    }

    /** @test */
    public function test_it_can_store_a_new_asisten()
    {
        $payload = [
            'name' => 'Test asisten',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tahun_ajaran' => 2024,
        ];

        $response = $this->postJson('/api/asisten', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'username' => 'Test asisten',
                'email' => 'test@example.com',
                'tahun_ajaran' => 2024,
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('asisten_datas', ['tahun_ajaran' => 2024]);
    }

    /** @test */
    public function test_it_can_update_a_asisten()
    {
        $user = User::asisten()->firstOrFail();

        $payload = [
            'name' => 'Updated Name',
            'tahun_ajaran' => 2024,
        ];

        $response = $this->putJson("/api/asisten/{$user->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'Updated Name',
                'tahun_ajaran' => 2024,
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
        $this->assertDatabaseHas('asisten_datas', ['user_id' => $user->id, 'tahun_ajaran' => 2024]);
    }

    /** @test */
    public function test_it_can_delete_a_asisten()
    {
        $user = User::asisten()->firstOrFail();

        $response = $this->deleteJson("/api/asisten/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('asisten_datas', ['user_id' => $user->id]);
    }
}
