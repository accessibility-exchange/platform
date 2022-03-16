<?php

namespace Tests\Feature;

use App\Models\DefinedTerm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DefinedTermTest extends TestCase
{
    use RefreshDatabase;

    public function test_defined_terms_appear_in_glossary()
    {
        $user = User::factory()->create();
        $term = DefinedTerm::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('defined-terms.index'));
        $response->assertSee($term->term);
    }
}
