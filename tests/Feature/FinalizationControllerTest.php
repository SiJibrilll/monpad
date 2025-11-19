<?php

namespace Tests\Feature;

use App\Models\GradeFinalization;
use App\Models\Group;
use App\Models\Project;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class FinalizationControllerTest extends TestCase
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
    public function test_it_can_finalize_grade()
    {
        $finalization = GradeFinalization::first();

        $response = $this->postJson("/api/finalization/{$finalization->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Grade Finalized Sucessfully']);
        
        $this->assertDatabaseHas('grade_finalizations', ['id' => $finalization->id, 'confirmed' => true]);
    }
}
