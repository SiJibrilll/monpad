<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();

        // $user = User::find(1);
         
        // // Fake-authenticate this user as Sanctum user with abilities
        // Sanctum::actingAs($user, ['*']); // or ->actingAs($user, ['create', 'update'])
    }

    /** @test */
    public function test_it_can_show_mahasiswa_dashboard()
    {
        // login sebagai mahasiswa
        $user = User::mahasiswa()->first();
        Sanctum::actingAs($user, ['*']);

        // get dashboard
        $response = $this->getJson('/api/dashboard/mahasiswa');
        // $response->dump();


        // $response->assertStatus(200)
        //     ->assertJsonStructure([
        //         'data' => [
        //             '*' => [
        //                 'id',
        //                 'username',
        //                 'email',
        //                 'nidn',
        //                 'fakultas',
        //             ]
        //         ]
        //     ]);
    }
    
    /** @test */
    public function test_it_can_show_asisten_dashboard()
    {
        // login sebagai mahasiswa
        $user = User::asisten()->first();
        Sanctum::actingAs($user, ['*']);

        // get dashboard
        $response = $this->getJson('/api/dashboard/asisten');
        // $response->dump();


        // $response->assertStatus(200)
        //     ->assertJsonStructure([
        //         'data' => [
        //             '*' => [
        //                 'id',
        //                 'username',
        //                 'email',
        //                 'nidn',
        //                 'fakultas',
        //             ]
        //         ]
        //     ]);
    }

    /** @test */
    public function test_it_can_show_dosen_dashboard()
    {
        // login sebagai mahasiswa
        $user = User::dosen()->first();
        Sanctum::actingAs($user, ['*']);

        // get dashboard
        $response = $this->getJson('/api/dashboard/dosen');
        $response->dump();


        // $response->assertStatus(200)
        //     ->assertJsonStructure([
        //         'data' => [
        //             '*' => [
        //                 'id',
        //                 'username',
        //                 'email',
        //                 'nidn',
        //                 'fakultas',
        //             ]
        //         ]
        //     ]);
    }
}
