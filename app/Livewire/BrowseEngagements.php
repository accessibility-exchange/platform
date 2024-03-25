<?php

namespace App\Livewire;

use App\Enums\Compensation;
use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\EngagementSignUpStatus;
use App\Enums\IdentityCluster;
use App\Enums\MeetingType;
use App\Enums\ProjectInitiator;
use App\Enums\ProvinceOrTerritory;
use App\Enums\SeekingForEngagement;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\LaravelOptions\Options;

class BrowseEngagements extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    public array $statuses = [];

    public array $formats = [];

    public array $seekings = [];

    public array $seekingGroups = [];

    public array $initiators = [];

    public array $meetingTypes = [];

    public array $locations = [];

    public array $compensations = [];

    public array $sectors = [];

    public array $impacts = [];

    public array $recruitmentMethods = [];

    protected $queryString = ['searchQuery' => ['except' => '', 'as' => 'search']];

    public function selectNone()
    {
        $this->statuses = [];
        $this->formats = [];
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
        return view('livewire.browse-engagements', [
            'engagements' => Engagement::with('project.projectable')
                ->status('published')
                ->when($this->searchQuery, function ($query, $searchQuery) {
                    $query->where(DB::raw('lower(name->"$.en")'), 'like', '%'.strtolower($searchQuery).'%')
                        ->orWhere(DB::raw('lower(name->"$.fr")'), 'like', '%'.strtolower($searchQuery).'%')
                        ->orWhereHas('project', function (Builder $projectQuery) use ($searchQuery) {
                            $projectQuery
                                ->whereHas('projectable', function (Builder $projectableQuery) use ($searchQuery) {
                                    $projectableQuery->where(DB::raw('lower(name->"$.en")'), 'like', '%'.strtolower($searchQuery).'%')
                                        ->orWhere(DB::raw('lower(name->"$.fr")'), 'like', '%'.strtolower($searchQuery).'%');
                                });
                        });
                })
                ->when($this->statuses, function ($query, $statuses) {
                    $query->statuses($statuses);
                })
                ->when($this->formats, function ($query, $formats) {
                    $query->formats($formats);
                })
                ->when($this->seekings, function ($query, $seekings) {
                    $query->seekings($seekings);
                })
                ->when($this->initiators, function ($query, $initiators) {
                    $query->initiators($initiators);
                })
                ->when($this->seekingGroups, function ($query, $seekingGroups) {
                    $query->seekingDisabilityAndDeafGroups($seekingGroups);
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
            'statusesData' => Options::forEnum(EngagementSignUpStatus::class)->toArray(),
            'formatsData' => Options::forEnum(EngagementFormat::class)->toArray(),
            'seekingsData' => Options::forEnum(SeekingForEngagement::class)->toArray(),
            'initiatorsData' => Options::forEnum(ProjectInitiator::class)->toArray(),
            'seekingGroupsData' => Options::forModels(Identity::query()
                ->whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf))->toArray(),
            'meetingTypesData' => Options::forEnum(MeetingType::class)->toArray(),
            'locationsData' => Options::forEnum(ProvinceOrTerritory::class)->toArray(),
            'compensationsData' => Options::forEnum(Compensation::class)->toArray(),
            'sectorsData' => Options::forModels(Sector::class)->toArray(),
            'impactedAreasData' => Options::forModels(Impact::class)->toArray(),
            'recruitmentMethodsData' => Options::forEnum(EngagementRecruitment::class)->toArray(),
        ])
            ->layout('layouts.app', ['bodyClass' => 'page', 'headerClass' => 'stack full header--engagements', 'pageWidth' => 'wide']);
    }

    public function search()
    {
        $this->resetPage();
    }
}
