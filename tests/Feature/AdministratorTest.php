<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

test('only administrators can access estimates and agreements admin page', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    actingAs($user)->get(localized_route('admin.estimates-and-agreements'))
        ->assertRedirect(localized_route('dashboard'));

    actingAs($administrator)->get(localized_route('admin.estimates-and-agreements'))
        ->assertOk();
});

test('log out from Filament redirects to standard login', function () {
    $administrator = User::factory()->create(['context' => 'administrator']);

    actingAs($administrator)->from(route('filament.admin.resources.interpretations.index'))->post(route('filament.admin.auth.logout'))
        ->assertRedirect(localized_route('login'));
});
