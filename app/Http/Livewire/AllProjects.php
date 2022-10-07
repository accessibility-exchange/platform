<?php

namespace App\Http\Livewire;

use App\Enums\EngagementRecruitment;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use App\Models\DisabilityType;
use App\Models\Impact;
use App\Models\Project;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\LaravelOptions\Options;

class AllProjects extends Component
{
    use WithPagination;

    public string $query = '';

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

    public function updatedStatuses()
    {
        if (! is_array($this->statuses)) {
            return;
        }
        $this->statuses = array_filter($this->statuses,
            function ($status) {
                return $status != false;
            }
        );
    }

    public function updatedSeekings()
    {
        if (! is_array($this->seekings)) {
            return;
        }
        $this->seekings = array_filter($this->seekings,
            function ($seeking) {
                return $seeking != false;
            }
        );
    }

    public function updatedInitiators()
    {
        if (! is_array($this->initiators)) {
            return;
        }
        $this->initiators = array_filter($this->initiators,
            function ($initiator) {
                return $initiator != false;
            }
        );
    }

    public function updatedSeekingGroups()
    {
        if (! is_array($this->seekingGroups)) {
            return;
        }
        $this->seekingGroups = array_filter($this->seekingGroups,
            function ($seekingGroup) {
                return $seekingGroup != false;
            }
        );
    }

    public function updatedMeetingTypes()
    {
        if (! is_array($this->meetingTypes)) {
            return;
        }
        $this->meetingTypes = array_filter($this->meetingTypes,
            function ($meetingType) {
                return $meetingType != false;
            }
        );
    }

    public function updatedLocations()
    {
        if (! is_array($this->locations)) {
            return;
        }
        $this->locations = array_filter($this->locations,
            function ($location) {
                return $location != false;
            }
        );
    }

    public function updatedCompensations()
    {
        if (! is_array($this->compensations)) {
            return;
        }
        $this->compensations = array_filter($this->compensations,
            function ($compensation) {
                return $compensation != false;
            }
        );
    }

    public function updatedSectors()
    {
        if (! is_array($this->sectors)) {
            return;
        }
        $this->sectors = array_filter($this->sectors,
            function ($sector) {
                return $sector != false;
            }
        );
    }

    public function updatedImpacts()
    {
        if (! is_array($this->impacts)) {
            return;
        }
        $this->impacts = array_filter($this->impacts,
            function ($impact) {
                return $impact != false;
            }
        );
    }

    public function updatedRecruitmentMethods()
    {
        if (! is_array($this->recruitmentMethods)) {
            return;
        }
        $this->recruitmentMethods = array_filter($this->recruitmentMethods,
            function ($recruitmentMethod) {
                return $recruitmentMethod != false;
            }
        );
    }

    public function render()
    {
        return view('livewire.all-projects', [
            'projects' => Project::status('published')
                    ->where(
                        function (Builder $query) {
                            $query->where('name->en', 'like', '%'.$this->query.'%')
                                ->orWhere('name->fr', 'like', '%'.$this->query.'%');
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
