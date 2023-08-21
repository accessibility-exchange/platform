<?php

use App\Models\Individual;

test('adding a translation succeeds for a valid translatable model', function () {
    $individual = Individual::factory()->create(['roles' => ['consultant']]);

    $response = $this->actingAs($individual->user)
        ->from(localized_route('individuals.edit', $individual))
        ->put(localized_route('translations.add'), [
            'translatable_type' => get_class($individual),
            'translatable_id' => $individual->id,
            'new_language' => 'asl',
        ]);

    $response->assertSessionHasNoErrors();
    $individual = $individual->fresh();

    expect(in_array('asl', $individual->languages))->toBeTrue();
    expect(flash()->class)->toBe('success');
    expect(flash()->message)->toBe(__('Language :language added.', ['language' => get_language_exonym('asl')]));
});

test('add translation validation errors', function ($data, $errors = null) {
    $individual = Individual::factory()->create([
        'name' => 'Tester',
        'roles' => ['consultant'],
    ]);

    $baseData = [
        'translatable_type' => get_class($individual),
        'translatable_id' => $individual->id,
        'new_language' => 'asl',
    ];

    $response = $this->actingAs($individual->user)
        ->put(localized_route('translations.add'), array_merge($baseData, $data));

    if (isset($errors)) {
        $response->assertSessionHasErrors($errors);
    } else {
        $response->assertForbidden();
    }
})->with('addTranslationRequestValidationErrors');

test('removing a translation succeeds for a valid translatable model', function () {
    $individual = Individual::factory()->create(['roles' => ['consultant']]);

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
    expect(flash()->class)->toBe('success');
    expect(flash()->message)->toBe(__('Language :language removed.', ['language' => get_language_exonym('fr')]));
});

test('destroy translation validation errors', function ($data, $errors = null) {
    $individual = Individual::factory()->create([
        'name' => 'Tester',
        'roles' => ['consultant'],
    ]);

    $baseData = [
        'translatable_type' => get_class($individual),
        'translatable_id' => $individual->id,
        'new_language' => 'asl',
    ];

    $response = $this->actingAs($individual->user)
        ->put(localized_route('translations.destroy'), array_merge($baseData, $data));

    if (isset($errors)) {
        $response->assertSessionHasErrors($errors);
    } else {
        $response->assertForbidden();
    }
})->with('destroyTranslationRequestValidationErrors');
