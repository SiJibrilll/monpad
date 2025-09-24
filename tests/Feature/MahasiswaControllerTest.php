<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MahasiswaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_list_all_mahasiswa()
    {
        $response = $this->getJson('/api/mahasiswa');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'username',
                        'email',
                        'nim',
                        'angkatan',
                        'prodi',
                        'jabatan',
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_mahasiswa()
    {
        $user = User::mahasiswa()->firstOrFail();

        $response = $this->getJson("/api/mahasiswa/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'username' => $user->name,
                    'email' => $user->email,
                    'nim' => $user->mahasiswa_data->nim,
                    'angkatan' => $user->mahasiswa_data->angkatan,
                    'prodi' => $user->mahasiswa_data->prodi,
                    'jabatan' => $user->mahasiswa_data->jabatan,
                ]
            ]);
    }

    /** @test */
    public function test_it_can_store_a_new_mahasiswa()
    {
        $payload = [
            'name' => 'Test Mahasiswa',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nim' => '987654321',
            'angkatan' => 2023,
            'prodi' => 'TI',
            'jabatan' => 'BE',
        ];

        $response = $this->postJson('/api/mahasiswa', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'username' => 'Test Mahasiswa',
                'email' => 'test@example.com',
                'nim' => '987654321',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('mahasiswa_datas', ['nim' => '987654321']);
    }

    /** @test */
    public function test_it_can_update_a_mahasiswa()
    {
        $user = User::mahasiswa()->firstOrFail();

        $payload = [
            'name' => 'Updated Name',
            'angkatan' => 2024,
        ];

        $response = $this->putJson("/api/mahasiswa/{$user->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'Updated Name',
                'angkatan' => 2024,
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
        $this->assertDatabaseHas('mahasiswa_datas', ['user_id' => $user->id, 'angkatan' => 2024]);
    }

    /** @test */
    public function test_it_can_delete_a_mahasiswa()
    {
        $user = User::mahasiswa()->firstOrFail();

        $response = $this->deleteJson("/api/mahasiswa/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('mahasiswa_datas', ['user_id' => $user->id]);
    }
}
