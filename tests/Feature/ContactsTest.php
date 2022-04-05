<?php

namespace Tests\Feature;

use App\Http\Livewire\Contacts;
use Livewire\Livewire;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    public function test_contact_can_be_added(): void
    {
        Livewire::test(Contacts::class, ['contacts' => [['name' => 'Daniel Addison', 'phone' => '1 (416) 555-5555', 'email' => 'daniel@example.com']]])
            ->call('addContact')
            ->assertSet('contacts', [['name' => 'Daniel Addison', 'phone' => '1 (416) 555-5555', 'email' => 'daniel@example.com'], ['name' => '', 'phone' => '', 'email' => '']]);
    }

    public function test_no_more_than_five_contacts_can_be_added(): void
    {
        Livewire::test(Contacts::class, ['contacts' => [
            ['name' => 'Daniel Addison', 'phone' => '1 (416) 555-5555', 'email' => 'daniel@example.com'],
            ['name' => 'Daniel Addison', 'phone' => '1 (416) 555-5555', 'email' => 'daniel@example.com'],
            ['name' => 'Daniel Addison', 'phone' => '1 (416) 555-5555', 'email' => 'daniel@example.com'],
            ['name' => 'Daniel Addison', 'phone' => '1 (416) 555-5555', 'email' => 'daniel@example.com'],
            ['name' => 'Daniel Addison', 'phone' => '1 (416) 555-5555', 'email' => 'daniel@example.com'],
        ]])
            ->call('addContact')
            ->assertCount('contacts', 5);
    }

    public function test_contact_can_be_removed(): void
    {
        Livewire::test(Contacts::class, ['contacts' => [['name' => 'Daniel Addison', 'phone' => '1 (416) 555-5555', 'email' => 'daniel@example.com']]])
            ->call('removeContact', 0)
            ->assertSet('contacts', []);
    }
}
