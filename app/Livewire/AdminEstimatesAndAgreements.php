<?php

namespace App\Livewire;

use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Notifications\AgreementReceived;
use App\Notifications\EstimateReturned;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminEstimatesAndAgreements extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    protected $queryString = ['searchQuery' => ['except' => '', 'as' => 'search']];

    public function render()
    {
        return view('livewire.admin-estimates-and-agreements', [
            'projects' => Project::whereNotNull('estimate_requested_at')
                ->with('projectable')
                ->when($this->searchQuery, function ($query, $searchQuery) {
                    $query->whereHasMorph(
                        'projectable',
                        [Organization::class, RegulatedOrganization::class],
                        function (Builder $projectableQuery) use ($searchQuery) {
                            $projectableQuery
                                ->where(DB::raw('lower(name->"$.en")'), 'like', '%'.strtolower($searchQuery).'%')
                                ->orWhere(DB::raw('lower(name->"$.fr")'), 'like', '%'.strtolower($searchQuery).'%');
                        });
                })
                ->orderBy('estimate_or_agreement_updated_at', 'desc')
                ->paginate(20),
        ])
            ->layout('layouts.app', ['bodyClass' => 'page', 'headerClass' => 'stack', 'pageWidth' => 'wide']);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function markEstimateReturned(int $id)
    {
        $project = Project::find($id);

        $project->update(['estimate_returned_at' => now()]);

        $project->notify(new EstimateReturned($project));

        $this->dispatch('clear-flash-message');

        session()->flash('message', __('The estimate for “:project” has been marked as returned.', ['project' => $project->getTranslation('name', locale())]));

        $this->dispatch('add-flash-message');
    }

    public function markAgreementReceived(int $id)
    {
        $project = Project::find($id);

        $project->update(['agreement_received_at' => now()]);

        $project->notify(new AgreementReceived($project));

        $this->dispatch('clear-flash-message');

        session()->flash('message', __('The agreement for “:project” has been marked as received.', ['project' => $project->getTranslation('name', locale())]));

        $this->dispatch('add-flash-message');
    }
}
