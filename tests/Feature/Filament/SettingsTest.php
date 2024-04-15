<?php

use App\Models\User;
use App\Settings\GeneralSettings;

use function Pest\Laravel\actingAs;

test('only administrative users can access the settings page', function () {
    GeneralSettings::fake(
        ['email' => 'support@accessibilityexchange.ca', 'individual_orientation' => ['en' => 'english link'], 'org_orientation' => ['en' => 'english link'], 'fro_orientation' => ['en' => 'english link'], 'ac_application' => ['en' => 'english link'], 'cc_application' => ['en' => 'english link']]
    );
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
