<?php

use App\Enums\UserContext;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => UserContext::Administrator->value]);
    $this->user = User::factory()->create();
});

test('default pulse route is not registered', function () {
    get('/pulse')->assertNotFound();
});

test('only administrators can access activity page', function () {
    actingAs($this->user)->get(route('filament.admin.pages.activity'))->assertForbidden();

    actingAs($this->admin)->get(route('filament.admin.pages.activity'))->assertSuccessful();
});
