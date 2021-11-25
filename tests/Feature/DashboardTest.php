<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_dashboard()
    {
        $user = User::factory()->create([
            'context' => 'consultant',
        ]);

        $response = $this->actingAs($user)->get(localized_route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Create your community member page');

        $user = User::factory()->create([
            'context' => 'entity',
        ]);

        $response = $this->actingAs($user)->get(localized_route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Create your entity page');
    }
}
