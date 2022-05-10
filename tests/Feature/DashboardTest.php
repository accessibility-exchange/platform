<?php

use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can access dashboard', function () {
    $user = User::factory()->create([
        'context' => 'community-member',
    ]);

    $response = $this->actingAs($user)->get(localized_route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Create your community member page');

    $user = User::factory()->create([
        'context' => 'regulated-organization',
    ]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Create your federally regulated organization page');
});
