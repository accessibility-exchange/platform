<?php

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can access dashboard', function () {
    $user = User::factory()->create([
        'context' => 'individual',
    ]);

    $individual = $user->individual;
    $individual->roles = ['participant'];
    $individual->save();

    $response = $this->actingAs($user)->get(localized_route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('My dashboard');

    $regulatedOrganizationUser = User::factory()->create([
        'context' => 'regulated-organization',
    ]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Create your federally regulated organization page');

    $organizationUser = User::factory()->create([
        'context' => 'organization',
    ]);

    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($organizationUser)->get(localized_route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Create your organization page');
});
