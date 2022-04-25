<?php

namespace App\Http\Livewire;

use App\Models\CommunityMember;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CommunicationPreferences extends Component
{
    public CommunityMember $communityMember;

    public string|null $email;

    public string|null $phone;

    public bool|null $vrs;

    public array $supportPeople;

    public string|null $contactPerson;

    public string|null $contactMethod;

    /**
     * @param CommunityMember $communityMember
     *
     * @return void
     */
    public function mount(CommunityMember $communityMember): void
    {
        $this->communityMember = $communityMember;
        $this->email = old('email', $communityMember->email ?? $communityMember->user->email);
        $this->phone = old('phone', $communityMember->phone ?? '');
        $this->vrs = old('vrs', $communityMember->vrs ?? false);
        $this->supportPeople = old('support_people', $communityMember->support_people ?? []);
        $this->contactPerson = old('preferred_contact_person', $communityMember->preferred_contact_person ?? 'me');
        $this->contactMethod = old('preferred_contact_method', $communityMember->preferred_contact_method ?? '');
    }

    public function getContactPeopleProperty(): array
    {
        $contactPeople = [
            '' => __('Choose a contact person…'),
            'me' => __('Me'),
        ];

        foreach ($this->supportPeople as $supportPerson) {
            if (isset($supportPerson['name'])) {
                $contactPeople[$supportPerson['name']] = $supportPerson['name'];
            }
        }

        return $contactPeople;
    }

    public function getContactMethodsFor(string $person = 'me'): array
    {
        $contactMethods = [
            '' => __('Choose a contact method…'),
        ];

        if ($person === 'me') {
            if ($this->email) {
                $contactMethods['email'] = __('Email');
            }

            if ($this->phone) {
                $contactMethods['phone'] = __('Phone');
            }

            if ($this->vrs) {
                $contactMethods['vrs'] = __('Video Relay Service (VRS)');
            }
        } else {
            $names = array_column($this->supportPeople, 'name');
            $key = array_search($person, $names);

            if (isset($this->supportPeople[$key]['email'])) {
                $contactMethods['email'] = __('Email');
            }

            if (isset($this->supportPeople[$key]['phone'])) {
                $contactMethods['phone'] = __('Phone');
            }
        }

        return $contactMethods;
    }

    public function addPerson(): void
    {
        if (! $this->canAddMorePeople()) {
            return;
        }

        $this->supportPeople[] = ['name' => '', 'phone' => '', 'email' => ''];
    }

    public function removePerson(int $i): void
    {
        unset($this->supportPeople[$i]);

        $this->supportPeople = array_values($this->supportPeople);
    }

    public function canAddMorePeople(): bool
    {
        return count($this->supportPeople) < 5;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.communication-preferences');
    }
}
