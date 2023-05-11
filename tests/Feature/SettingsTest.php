<?php

use App\Models\User;

test('only administrative users can access the settings page', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $response = $this->actingAs($user)->get(route('filament.pages.settings'));
    $response->assertForbidden();

    $response = $this->actingAs($administrator)->get(route('filament.pages.settings'));
    $response->assertSuccessful();
    $response->assertSeeInOrder([
        'Website settings',
        'Contact',
        'Support email',
        'Support phone',
        'Mailing address',
        'Social media',
        'Facebook page',
        'LinkedIn page',
        'Twitter page',
        'YouTube page',
        'Registration',
        'Individual orientation',
        'Community organization orientation',
        'Federally regulated organization orientation',
        'Accessibility consultant application',
        'Community connector application',
    ]);
});
