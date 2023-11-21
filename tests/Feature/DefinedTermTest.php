<?php

use App\Models\DefinedTerm;
use App\Models\User;

test('defined terms appear in glossary', function () {
    $user = User::factory()->create();
    $term = DefinedTerm::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('about.defined-terms.index'));
    $response->assertSee($term->term);
});
