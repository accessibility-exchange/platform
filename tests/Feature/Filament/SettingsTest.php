<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

test('only administrative users can access the settings page', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    actingAs($user)->get(route('filament.admin.pages.settings'))
        ->assertForbidden();

    actingAs($administrator)->get(route('filament.admin.pages.settings'))
        ->assertSuccessful()
        ->assertSeeInOrder([
            'Website settings',
            'Contact',
            'Support email',
            'Support phone',
            'Privacy email',
            'Mailing address',
            'Social media',
            'Facebook page',
            'LinkedIn page',
            'Twitter page',
            'YouTube page',
            'Registration',
            'Individual orientation',
            'English',
            'French',
            'Community organization orientation',
            'English',
            'French',
            'Federally regulated organization orientation',
            'English',
            'French',
            'Accessibility Consultant and Community Connector application',
            'English',
            'French',
        ]);
});
