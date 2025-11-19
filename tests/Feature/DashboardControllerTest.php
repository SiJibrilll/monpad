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
    }

    /** @test */
    public function test_it_can_show_mahasiswa_dashboard()
    {
        // login sebagai mahasiswa
        $user = User::mahasiswa()->first();
        Sanctum::actingAs($user, ['*']);

        // get dashboard
        $response = $this->getJson('/api/dashboard/mahasiswa');


        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'groups' => [
                    '*' => [
                        'nama',
                        'anggota' => [
                            '*' => [
                                'nim'
                            ]
                            ],
                        'project' => [
                            'nama_projek'
                        ],
                    ]
                ],
                'grades'
            ]
        ]);
    }
    
    /** @test */
    public function test_it_can_show_asisten_dashboard()
    {
        // login sebagai mahasiswa
        $user = User::asisten()->first();
        Sanctum::actingAs($user, ['*']);

        // get dashboard
        $response = $this->getJson('/api/dashboard/asisten');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'jumlah_mahasiswa',
                    'rata_rata'
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_dosen_dashboard()
    {
        // login sebagai mahasiswa
        $user = User::dosen()->first();
        Sanctum::actingAs($user, ['*']);

        // get dashboard
        $response = $this->getJson('/api/dashboard/dosen');


        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'jumlah_mahasiswa',
                    'jumlah_asisten',
                    'jumlah_projek',
                    'rata_rata'
                ]
            ]);
    }
}
