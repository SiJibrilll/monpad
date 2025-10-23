<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\GradeType;
use Tests\TestCase;
use App\Models\User;
use App\Models\Week;
use App\Models\WeekType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class GradeNoteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with dummy data
        $this->seed();
    }

    /** @test */
    public function test_it_can_list_all_notes_in_a_week()
    {
        $week = Week::first();
        $response = $this->getJson("/api/week/{$week->id}/review");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'note',
                        'writer' => [
                            'username'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_it_can_show_a_single_week_note()
    {
        $week = Week::first();
        $review = $week->review()->first();

        $response = $this->getJson("/api/week/{$week->id}/review/{$review->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'note',
                    'writer' => [
                        'username'
                    ]                    
                ]
            ]);
    }

    /** @test */
    public function test_it_can_store_a_new_week_note()
    {
        $week = Week::first();
        $dosen = User::dosen()->first();

        
        Sanctum::actingAs($dosen, ['*']); // or ->actingAs($user, ['create', 'update'])

        $payload = [
            'note' => 'Kurangi nilainya, mahasiswanya kurang sopan',
        ];

        $response = $this->postJson("/api/week/{$week->id}/review", $payload);


        $response->assertStatus(201)
            ->assertJsonFragment([
                'note' => $payload['note'],
                'username' => $dosen->name
            ]);

        $this->assertDatabaseHas('grade_notes', ['note' => $payload['note'], 'writer_id' => $dosen->id, 'week_id' => $week->id]);
    }

    /** @test */
    public function test_it_can_update_a_week_note()
    {
        $week = Week::first();
        $review = $week->review()->first();
        $dosen = User::dosen()->first();

        
        Sanctum::actingAs($dosen, ['*']); // or ->actingAs($user, ['create', 'update'])

        $payload = [
            'note' => 'Mahasiswanya sudah minta maaf, boleh dikasih nilai bagus',
        ];

        $response = $this->putJson("/api/week/{$week->id}/review/{$review->id}", $payload);


        $response->assertStatus(200)
            ->assertJsonFragment([
                'note' => $payload['note'],
                'username' => $dosen->name
            ]);

        $this->assertDatabaseHas('grade_notes', ['note' => $payload['note'], 'writer_id' => $dosen->id, 'week_id' => $week->id]);
    }

    /** @test */
    public function test_it_can_delete_a_week_type()
    {
        $week = Week::first();
        $review = $week->review()->first();
        $dosen = User::dosen()->first();
        Sanctum::actingAs($dosen, ['*']); // or ->actingAs($user, ['create', 'update'])

        $response = $this->deleteJson("/api/week/{$week->id}/review/{$review->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('grade_notes', ['note' => $week->id, 'writer_id' => $dosen->id, 'week_id' => $week->id]);

    }
}
