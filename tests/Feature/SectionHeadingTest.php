<?php

use App\Enums\IndividualRole;
use App\Models\Individual;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->individual = Individual::factory()->create([
        'roles' => [IndividualRole::CommunityConnector->value],
    ]);
});

test('Section heading for authorized user', function () {
    actingAs($this->individual->user)->blade(
        '<x-section-heading :name="$name" :model="$model" :href="$href" />',
        [
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
        ]
    )->assertSeeInOrder([
        'h2',
        'Testing',
        'href="http://example.com"',
        'Edit <span class="visually-hidden">Testing</span>',
    ], false);
});

test('Section heading for unauthorized user', function () {
    $user = User::factory()->create();

    actingAs($user)->blade(
        '<x-section-heading :name="$name" :model="$model" :href="$href" />',
        [
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
        ]
    )
        ->assertSeeInOrder([
            'h2',
            'Testing',
        ], false)
        ->assertDontSee('href="http://example.com"', false)
        ->assertDontSee('Edit <span class="visually-hidden">Testing</span>', false);
});

test('Custom section heading level', function () {
    actingAs($this->individual->user)->blade(
        '<x-section-heading :level="$level" :name="$name" :model="$model" :href="$href" />',
        [
            'level' => 3,
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
        ]
    )
        ->assertSeeInOrder([
            'h3',
            'Testing',
            'href="http://example.com"',
            'Edit <span class="visually-hidden">Testing</span>',
        ], false)
        ->assertDontSee('h2', false);
});

test('Different link text', function () {
    actingAs($this->individual->user)->blade(
        '<x-section-heading :name="$name" :model="$model" :href="$href" :linkText="$linkText"  />',
        [
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
            'linkText' => 'Custom link',
        ]
    )
        ->assertSeeInOrder([
            'h2',
            'Testing',
            'href="http://example.com"',
            'Edit <span class="visually-hidden">Custom link</span>',
        ], false)
        ->assertDontSee('Edit <span class="visually-hidden">Testing</span>', false);
});

test('Escaped link text', function () {
    actingAs($this->individual->user)->blade(
        '<x-section-heading :name="$name" :model="$model" :href="$href" :linkText="$linkText"  />',
        [
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
            'linkText' => '<strong>Link</strong>',
        ]
    )
        ->assertSeeInOrder([
            'h2',
            'Testing',
            'href="http://example.com"',
            'Edit <span class="visually-hidden">&lt;strong&gt;Link&lt;/strong&gt;</span>',
        ], false)
        ->assertDontSee('<strong>Link</strong>', false);
});
