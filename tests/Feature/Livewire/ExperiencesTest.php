<?php

use App\Livewire\Experiences;

use function Pest\Livewire\livewire;

test('experiences can be added', function () {
    livewire(Experiences::class, ['experiences' => []])
        ->call('addExperience')
        ->assertSet('experiences', [['title' => '', 'organization' => '', 'start_year' => '', 'end_year' => '', 'current' => false]]);
});

test('group can have no more than 20 experiences', function () {
    livewire(Experiences::class, ['experiences' => [
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
    ]])
        ->call('addExperience')
        ->assertCount('experiences', 20);
});

test('experiences can be removed', function () {
    livewire(Experiences::class, ['experiences' => [
        ['title' => 'Some job', 'organization' => 'Some place', 'start_year' => '2020', 'end_year' => '2022', 'current' => false],
    ]])
        ->call('removeExperience', 0)
        ->assertSet('experiences', []);
});
