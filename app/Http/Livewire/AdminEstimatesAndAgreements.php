<?php

namespace App\Http\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class AdminEstimatesAndAgreements extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin-estimates-and-agreements', [
            'projects' => Project::whereNotNull('estimate_requested_at')->paginate(20),
        ])
            ->layout('layouts.app-wide');
    }

    public function search()
    {
        $this->resetPage();
    }

    public function markEstimateReturned()
    {
        // TODO
    }

    public function markAgreementReceived()
    {
        // TODO
    }
}
