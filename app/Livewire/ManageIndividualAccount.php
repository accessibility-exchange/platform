<?php

namespace App\Livewire;

use App\Models\Individual;
use App\Models\User;
use App\Notifications\AccountApproved;
use App\Notifications\AccountSuspended;
use App\Notifications\AccountUnsuspended;
use Livewire\Component;

class ManageIndividualAccount extends Component
{
    public User $user;

    public Individual $individual;

    public function mount()
    {
        $this->individual = $this->user->individual;
    }

    public function render()
    {
        return view('livewire.manage-individual-account');
    }

    public function approve()
    {
        $this->user->update(['oriented_at' => now()]);

        $this->user->notify(new AccountApproved($this->individual));
        $this->dispatch('flashMessage', __(':account has been approved.', ['account' => $this->individual->name]), __('Account has been approved.', [], 'en'));
    }

    public function suspend()
    {
        $this->user->update(['suspended_at' => now()]);

        $this->user->notify(new AccountSuspended($this->individual));
        $this->dispatch('flashMessage', __(':account has been suspended.', ['account' => $this->individual->name]), __('Account has been suspended.', [], 'en'));
    }

    public function unsuspend()
    {
        $this->user->update(['suspended_at' => null]);

        $this->user->notify(new AccountUnsuspended($this->individual));
        $this->dispatch('flashMessage', __('The suspension of :account has been lifted.', ['account' => $this->individual->name]), __('The suspension has been lifted.', [], 'en'));
    }
}
