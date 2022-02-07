<?php

namespace Tests\Feature;

use App\Http\Livewire\SupportPeople;
use Livewire\Livewire;
use Tests\TestCase;

class SupportPeopleTest extends TestCase
{
    public function test_people_can_be_added(): void
    {
        Livewire::test(SupportPeople::class, ['people' => []])
            ->call('addPerson')
            ->assertSet('people', [['name' => '', 'phone' => '', 'email' => '']]);
    }

    public function test_no_more_than_five_people_can_be_added(): void
    {
        Livewire::test(SupportPeople::class, ['people' => [
            ['name' => 'Person 1', 'phone' => '438-123-4567', 'email' => 'person1@example.com'],
            ['name' => 'Person 2', 'phone' => '438-123-4567', 'email' => 'person2@example.com'],
            ['name' => 'Person 3', 'phone' => '438-123-4567', 'email' => 'person3@example.com'],
            ['name' => 'Person 4', 'phone' => '438-123-4567', 'email' => 'person4@example.com'],
            ['name' => 'Person 5', 'phone' => '438-123-4567', 'email' => 'person5@example.com'],
        ]])
            ->call('addPerson')
            ->assertCount('people', 5);
    }

    public function test_person_can_be_removed(): void
    {
        Livewire::test(SupportPeople::class, ['people' => [
            ['name' => 'Person 1', 'phone' => '438-123-4567', 'email' => 'person1@example.com'],
        ]])
            ->call('removePerson', 0)
            ->assertSet('people', []);
    }
}
