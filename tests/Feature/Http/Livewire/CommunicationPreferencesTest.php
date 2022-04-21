<?php

use App\Http\Livewire\CommunicationPreferences;
use App\Models\CommunityMember;

test('support people can be added', function () {
    $communityMember = CommunityMember::factory()->create();

    $this->livewire(CommunicationPreferences::class, ['communityMember' => $communityMember])
        ->call('addPerson')
        ->assertSet('supportPeople', [['name' => '', 'email' => '', 'phone' => '']]);
});

test('no more than five people can be added', function () {
    $communityMember = CommunityMember::factory()->create([
        'support_people' => [
            ['name' => 'Person 1', 'phone' => '438-123-4567', 'email' => 'person1@example.com'],
            ['name' => 'Person 2', 'phone' => '438-123-4567', 'email' => 'person2@example.com'],
            ['name' => 'Person 3', 'phone' => '438-123-4567', 'email' => 'person3@example.com'],
            ['name' => 'Person 4', 'phone' => '438-123-4567', 'email' => 'person4@example.com'],
            ['name' => 'Person 5', 'phone' => '438-123-4567', 'email' => 'person5@example.com'],
        ],
    ]);

    $this->livewire(CommunicationPreferences::class, ['communityMember' => $communityMember])
        ->call('addPerson')
        ->assertCount('supportPeople', 5);
});

test('a person can be removed', function () {
    $communityMember = CommunityMember::factory()->create([
        'support_people' => [
            ['name' => 'Person 1', 'phone' => '438-123-4567', 'email' => 'person1@example.com'],
        ],
    ]);

    $this->livewire(CommunicationPreferences::class, ['communityMember' => $communityMember])
        ->call('removePerson', 0)
        ->assertSet('supportPeople', []);
});
