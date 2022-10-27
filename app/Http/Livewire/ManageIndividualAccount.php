<?php

namespace App\Http\Livewire;

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
        $this->emit('flashMessage', __(':account has been approved.', ['account' => $this->individual->name]));
    }

    public function suspend()
    {
        $this->user->update(['suspended_at' => now()]);

        $this->user->notify(new AccountSuspended($this->individual));
        $this->emit('flashMessage', __(':account has been suspended.', ['account' => $this->individual->name]));
    }

    public function unsuspend()
    {
        $this->user->update(['suspended_at' => null]);

        $this->user->notify(new AccountUnsuspended($this->individual));
        $this->emit('flashMessage', __('The suspension of :account has been lifted.', ['account' => $this->individual->name]));
    }
}
