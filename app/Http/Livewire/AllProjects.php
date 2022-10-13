<?php

namespace App\Http\Livewire;

use App\Enums\EngagementRecruitment;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use App\Models\DisabilityType;
use App\Models\Impact;
use App\Models\Project;
use App\Models\Sector;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\LaravelOptions\Options;

class AllProjects extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    public array $statuses = [];

    public array $seekings = [];

    public array $seekingGroups = [];

    public array $initiators = [];

    public array $meetingTypes = [];

    public array $locations = [];

    public array $compensations = [];

    public array $sectors = [];

    public array $impacts = [];

    public array $recruitmentMethods = [];

    public function updatedStatuses($status)
    {
        $this->statuses = $this->filterItems($this->statuses);
    }

    public function updatedSeekings()
    {
        $this->seekings = $this->filterItems($this->seekings);
    }

    public function updatedInitiators()
    {
        $this->initiators = $this->filterItems($this->initiators);
    }

    public function updatedSeekingGroups()
    {
        $this->seekingGroups = $this->filterItems($this->seekingGroups);
    }

    public function updatedMeetingTypes()
    {
        $this->meetingTypes = $this->filterItems($this->meetingTypes);
    }

    public function updatedLocations()
    {
        $this->locations = $this->filterItems($this->locations);
    }

    public function updatedCompensations()
    {
        $this->compensations = $this->filterItems($this->compensations);
    }

    public function updatedSectors()
    {
        $this->sectors = $this->filterItems($this->sectors);
    }

    public function updatedImpacts()
    {
        $this->impacts = $this->filterItems($this->impacts);
    }

    public function updatedRecruitmentMethods()
    {
        $this->recruitmentMethods = $this->filterItems($this->recruitmentMethods);
    }

    public function filterItems(array $items)
    {
        return array_filter($items, function ($item) {
            return $item != false;
        });
    }

    public function selectNone()
    {
        $this->statuses = [];
        $this->seekings = [];
        $this->seekingGroups = [];
        $this->initiators = [];
        $this->meetingTypes = [];
        $this->locations = [];
        $this->compensations = [];
        $this->sectors = [];
        $this->impacts = [];
        $this->recruitmentMethods = [];
    }

    public function render()
    {
        return view('livewire.all-projects', [
            'projects' => Project::status('published')
                    ->when($this->searchQuery, function ($query, $searchQuery) {
                        $query->where('name->en', 'like', '%'.$searchQuery.'%')
                            ->orWhere('name->fr', 'like', '%'.$searchQuery.'%');
                    })
                    ->when($this->statuses, function ($query, $statuses) {
                        $query->statuses($statuses);
                    })
                    ->when($this->seekings, function ($query, $seekings) {
                        $query->seekings($seekings);
                    })
                    ->when($this->initiators, function ($query, $initiators) {
                        $query->initiators($initiators);
                    })
                    ->when($this->seekingGroups, function ($query, $seekingGroups) {
                        $query->seekingGroups($seekingGroups);
                    })
                    ->when($this->meetingTypes, function ($query, $meetingTypes) {
                        $query->meetingTypes($meetingTypes);
                    })
                    ->when($this->locations, function ($query, $locations) {
                        $query->locations($locations);
                    })
                    ->when($this->compensations, function ($query, $compensations) {
                        $query->compensations($compensations);
                    })
                    ->when($this->sectors, function ($query, $sectors) {
                        $query->sectors($sectors);
                    })
                    ->when($this->impacts, function ($query, $impacts) {
                        $query->areasOfImpact($impacts);
                    })
                    ->when($this->recruitmentMethods, function ($query, $recruitmentMethods) {
                        $query->recruitmentMethods($recruitmentMethods);
                    })
                    ->orderBy('published_at', 'desc')
                    ->paginate(20),
            'statusesData' => [
                ['value' => 'upcoming', 'label' => __('Upcoming')],
                ['value' => 'inProgress', 'label' => __('In Progress')],
                ['value' => 'completed', 'label' => __('Completed')],
            ],
            'seekingsData' => [
                ['value' => 'participants', 'label' => __('Seeking Individual Consultation Participatns')],
                ['value' => 'connectors', 'label' => __('Seeking Community Connectors')],
                ['value' => 'organizations', 'label' => __('Seeking Community Organizations to Consult with')],
            ],
            'initiatorsData' => [
                ['value' => 'organization', 'label' => __('Community organization')],
                ['value' => 'regulatedOrganization', 'label' => __('Regulated organization')],
            ],
            'seekingGroupsData' => Options::forModels(DisabilityType::class)->toArray(),
            'meetingTypesData' => Options::forEnum(MeetingType::class)->toArray(),
            'locationsData' => Options::forEnum(ProvinceOrTerritory::class)->toArray(),
            'compensationsData' => [
                ['value' => 'paid', 'label' => __('Paid')],
                ['value' => 'volunteer', 'label' => __('Volunteer')],
            ],
            'sectorsData' => Options::forModels(Sector::class)->toArray(),
            'impactedAreasData' => Options::forModels(Impact::class)->toArray(),
            'recruitmentMethodsData' => Options::forEnum(EngagementRecruitment::class)->toArray(),
        ])
            ->layout('layouts.app-wide');
    }

    public function search()
    {
        $this->resetPage();
    }
}
