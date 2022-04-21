<?php

use App\Http\Livewire\SupportPeople;

test('people can be added', function () {
    $this->livewire(SupportPeople::class, ['people' => []])
        ->call('addPerson')
        ->assertSet('people', [['name' => '', 'phone' => '', 'email' => '']]);
});

test('no more than five people can be added', function () {
    $this->livewire(SupportPeople::class, ['people' => [
        ['name' => 'Person 1', 'phone' => '438-123-4567', 'email' => 'person1@example.com'],
        ['name' => 'Person 2', 'phone' => '438-123-4567', 'email' => 'person2@example.com'],
        ['name' => 'Person 3', 'phone' => '438-123-4567', 'email' => 'person3@example.com'],
        ['name' => 'Person 4', 'phone' => '438-123-4567', 'email' => 'person4@example.com'],
        ['name' => 'Person 5', 'phone' => '438-123-4567', 'email' => 'person5@example.com'],
    ]])
        ->call('addPerson')
        ->assertCount('people', 5);
});

test('a person can be removed', function () {
    $this->livewire(SupportPeople::class, ['people' => [
        ['name' => 'Person 1', 'phone' => '438-123-4567', 'email' => 'person1@example.com'],
    ]])
        ->call('removePerson', 0)
        ->assertSet('people', []);
});
