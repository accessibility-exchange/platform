<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JoinTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_to_join_team()
    {
        $user = User::factory()->create(['context' => 'organization']);
        $organization = Organization::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
        $response->assertSee('Request to join');

        $response = $this->actingAs($user)
            ->from(localized_route('organizations.show', $organization))
            ->post(localized_route('organizations.join', $organization));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('organizations.show', $organization));

        $organization = $organization->fresh();

        $this->assertEquals(1, $organization->requestsToJoin->count());
        $this->assertEquals($user->id, $organization->requestsToJoin->first()->id);
    }

    public function test_user_with_outstanding_join_request_cannot_request_to_join_team()
    {
        $user = User::factory()->create(['context' => 'organization']);
        $organization = Organization::factory()->create();
        $organization->requestsToJoin()->save($user);
        $otherOrganization = Organization::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('organizations.show', $otherOrganization));
        $response->assertDontSee('Request to join');

        $response = $this->actingAs($user)
            ->from(localized_route('organizations.show', $otherOrganization))
            ->post(localized_route('organizations.join', $otherOrganization));

        $response->assertForbidden();
    }

    public function test_user_with_existing_membership_cannot_request_to_join_team()
    {
        $user = User::factory()->create(['context' => 'organization']);
        $organization = Organization::factory()->create();
        $organization->users()->attach($user, ['role' => 'admin']);
        $otherOrganization = Organization::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('organizations.show', $otherOrganization));
        $response->assertDontSee('Request to join');

        $response = $this->actingAs($user)
            ->from(localized_route('organizations.show', $otherOrganization))
            ->post(localized_route('organizations.join', $otherOrganization));

        $response->assertForbidden();
    }

    public function test_user_can_cancel_request_to_join_team()
    {
        $user = User::factory()->create(['context' => 'organization']);
        $organization = Organization::factory()->create();
        $organization->requestsToJoin()->save($user);

        $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
        $response->assertSee('Cancel request');

        $response = $this->actingAs($user)->post(localized_route('requests.cancel'));
        $response->assertRedirect(localized_route('organizations.show', $organization));

        $user = $user->fresh();
        $organization = $organization->fresh();

        $this->assertNull($user->joinable);
        $this->assertEquals(0, $organization->requestsToJoin->count());
    }

    public function test_admin_can_approve_request_to_join_team()
    {
        $user = User::factory()->create(['context' => 'organization']);
        $admin = User::factory()->create(['context' => 'organization']);
        $organization = Organization::factory()->hasAttached($admin, ['role' => 'admin'])->create();
        $organization->requestsToJoin()->save($user);

        $response = $this->actingAs($admin)->get(localized_route('organizations.edit', $organization));
        $response->assertSee('Approve ' . $user->name . '’s request');

        $response = $this->actingAs($admin)->post(localized_route('requests.approve', $user));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('organizations.edit', $organization));

        $user = $user->fresh();
        $organization = $organization->fresh();

        $this->assertNull($user->joinable);
        $this->assertTrue($organization->hasUserWithEmail($user->email));
    }

    public function test_admin_can_deny_request_to_join_team()
    {
        $user = User::factory()->create(['context' => 'organization']);
        $admin = User::factory()->create(['context' => 'organization']);
        $organization = Organization::factory()->hasAttached($admin, ['role' => 'admin'])->create();
        $organization->requestsToJoin()->save($user);

        $response = $this->actingAs($admin)->get(localized_route('organizations.edit', $organization));
        $response->assertSee('Deny ' . $user->name . '’s request');

        $response = $this->actingAs($admin)->post(localized_route('requests.deny', $user));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('organizations.edit', $organization));

        $user = $user->fresh();
        $organization = $organization->fresh();

        $this->assertNull($user->joinable);
        $this->assertFalse($organization->hasUserWithEmail($user->email));
    }
}
