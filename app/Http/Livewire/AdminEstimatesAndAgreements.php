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
                        })
                    ->orderBy('estimate_or_agreement_updated_at', 'desc')
                    ->paginate(20)
                : Project::whereNotNull('estimate_requested_at')
                    ->orderBy('estimate_or_agreement_updated_at', 'desc')
                    ->paginate(20),
        ])
            ->layout('layouts.app-wide');
    }

    public function search()
    {
        $this->resetPage();
    }

    public function markEstimateReturned(int $id)
    {
        $project = Project::find($id);

        $project->update(['estimate_returned_at' => now()]);

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __('The estimate for “:project” has been marked as returned.', ['project' => $project->name]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function markAgreementReceived(int $id)
    {
        $project = Project::find($id);

        $project->update(['agreement_received_at' => now()]);

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __('The agreement for “:project” has been marked as received.', ['project' => $project->name]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }
}
