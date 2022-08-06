<?php

use App\Models\Individual;
use App\Models\IndividualRole;
use Database\Seeders\IndividualRoleSeeder;

test('adding a translation succeeds for a valid translatable model', function () {
    $this->seed(IndividualRoleSeeder::class);
    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual = Individual::factory()->create();
    $individual->individualRoles()->sync([
        $consultantRole->id,
    ]);

    $response = $this->actingAs($individual->user)
        ->from(localized_route('individuals.edit', $individual))
        ->put(localized_route('translations.add'), [
            'translatable_type' => get_class($individual),
            'translatable_id' => $individual->id,
            'new_language' => 'ase',
        ]);

    $response->assertSessionHasNoErrors();
    $individual = $individual->fresh();

    expect(in_array('ase', $individual->languages))->toBeTrue();
});

test('removing a translation succeeds for a valid translatable model', function () {
    $this->seed(IndividualRoleSeeder::class);
    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual = Individual::factory()->create();
    $individual->individualRoles()->sync([
        $consultantRole->id,
    ]);

    $response = $this->actingAs($individual->user)
        ->from(localized_route('individuals.edit', $individual))
        ->put(localized_route('translations.destroy'), [
            'translatable_type' => get_class($individual),
            'translatable_id' => $individual->id,
            'language' => 'fr',
        ]);

    $response->assertSessionHasNoErrors();
    $individual = $individual->fresh();

    expect(in_array('fr', $individual->languages))->toBeFalse();
});
