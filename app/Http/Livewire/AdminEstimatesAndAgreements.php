<?php

namespace App\Http\Livewire;

use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class AdminEstimatesAndAgreements extends Component
{
    use WithPagination;

    public string $query = '';

    public function render()
    {
        return view('livewire.admin-estimates-and-agreements', [
            'projects' => $this->query
                ? Project::whereNotNull('estimate_requested_at')
                    ->whereHasMorph(
                        'projectable',
                        [Organization::class, RegulatedOrganization::class],
                        function (Builder $query) {
                            $query->where('name->en', 'like', '%'.$this->query.'%')
                                ->orWhere('name->fr', 'like', '%'.$this->query.'%');
                        }
                    )
                    ->paginate(20)
                : Project::whereNotNull('estimate_requested_at')->paginate(20),
        ])
            ->layout('layouts.app-wide');
    }

    public function search()
    {
        $this->resetPage();
    }

    public function markEstimateReturned(int $id)
    {
        Project::find($id)->update(['estimate_returned_at' => now()]);
    }

    public function markAgreementReceived(int $id)
    {
        Project::find($id)->update(['agreement_received_at' => now()]);
    }
}
