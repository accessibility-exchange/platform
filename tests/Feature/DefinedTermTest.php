<?php

use App\Models\DefinedTerm;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('defined terms appear in glossary', function () {
    $user = User::factory()->create();
    $term = DefinedTerm::factory()->create();

    actingAs($user)->get(localized_route('about.defined-terms.index'))
        ->assertSee($term->term);
});
