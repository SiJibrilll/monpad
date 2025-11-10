<?php

namespace Tests\Feature;

use App\Models\Project;
use Tests\TestCase;
use App\Models\User;
use App\Models\Week;
use App\Models\WeekType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_login()
    {
        $payload = [
            'username' => 'dosen1',
            'email' => 'dosen1@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'username'
                ],
                'token'
            ]
        ]);
    }

    /** @test */
    public function test_it_can_logout()
    {
        $user = User::find(1);
        Sanctum::actingAs($user, ['*']); 

        $response = $this->postJson('/api/logout');

         $response->assertStatus(200)
             ->assertJson(['message' => 'Logged out successfully']);

        $this->assertCount(0, $user->tokens);
    }
}
