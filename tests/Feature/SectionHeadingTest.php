<?php

use App\Enums\IndividualRole;
use App\Models\Individual;
use App\Models\User;

beforeEach(function () {
    $this->individual = Individual::factory()->create([
        'roles' => [IndividualRole::CommunityConnector->value],
    ]);
});

test('Section heading for authorized user', function () {
    $view = $this->actingAs($this->individual->user)->blade(
        '<x-section-heading :name="$name" :model="$model" :href="$href" />',
        [
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
        ]
    );

    $view->assertSeeInOrder([
        'h2',
        'Testing',
        'href="http://example.com"',
        'Edit <span class="visually-hidden">Testing</span>',
    ], false);
});

test('Section heading for unauthorized user', function () {
    $user = User::factory()->create();

    $view = $this->actingAs($user)->blade(
        '<x-section-heading :name="$name" :model="$model" :href="$href" />',
        [
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
        ]
    );

    $view->assertSeeInOrder([
        'h2',
        'Testing',
    ], false);

    $view->assertDontSee('href="http://example.com"', false);
    $view->assertDontSee('Edit <span class="visually-hidden">Testing</span>', false);
});

test('Custom section heading level', function () {
    $view = $this->actingAs($this->individual->user)->blade(
        '<x-section-heading :level="$level" :name="$name" :model="$model" :href="$href" />',
        [
            'level' => 3,
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
        ]
    );

    $view->assertSeeInOrder([
        'h3',
        'Testing',
        'href="http://example.com"',
        'Edit <span class="visually-hidden">Testing</span>',
    ], false);

    $view->assertDontSee('h2', false);
});

test('Different link text', function () {
    $view = $this->actingAs($this->individual->user)->blade(
        '<x-section-heading :name="$name" :model="$model" :href="$href" :linkText="$linkText"  />',
        [
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
            'linkText' => 'Custom link',
        ]
    );

    $view->assertSeeInOrder([
        'h2',
        'Testing',
        'href="http://example.com"',
        'Edit <span class="visually-hidden">Custom link</span>',
    ], false);

    $view->assertDontSee('Edit <span class="visually-hidden">Testing</span>', false);
});

test('Escaped link text', function () {
    $view = $this->actingAs($this->individual->user)->blade(
        '<x-section-heading :name="$name" :model="$model" :href="$href" :linkText="$linkText"  />',
        [
            'name' => 'Testing',
            'href' => 'http://example.com',
            'model' => $this->individual,
            'linkText' => '<strong>Link</strong>',
        ]
    );

    $view->assertSeeInOrder([
        'h2',
        'Testing',
        'href="http://example.com"',
        'Edit <span class="visually-hidden">&lt;strong&gt;Link&lt;/strong&gt;</span>',
    ], false);

    $view->assertDontSee('<strong>Link</strong>', false);
});
