<?php

namespace App\Livewire;

use App\Enums\ConsultationPhase;
use App\Models\Impact;
use App\Models\Library;
use App\Models\ResourceType;
use App\Models\Sector;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\LaravelOptions\Options;

class LibraryResources extends Component
{
    use WithPagination;

    public Library $library;

    public string $searchQuery = '';

    public array $resourceTypes = [];

    public array $impacts = [];

    public array $languages = [];

    public array $phases = [];

    public array $sectors = [];

    public array $topics = [];

    protected $queryString = ['searchQuery' => ['except' => '', 'as' => 'search']];

    public function mount(Library $library)
    {
        $this->library = $library;
    }

    public function selectNone()
    {
        $this->resourceTypes = [];
        $this->impacts = [];
        $this->languages = [];
        $this->phases = [];
        $this->sectors = [];
        $this->topics = [];
    }

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.library-resources', [
            'resourceCollections' => $this->library->resourceCollections
                ->paginate(10, pageName: 'collections-page'),
            'resources' => $this->library->resources()->when($this->searchQuery, function ($query, $searchQuery) {
                $query->where(DB::raw('lower(`resources`.`title`->"$.en")'), 'like', '%'.strtolower($searchQuery).'%')
                    ->orWhere(DB::raw('lower(`resources`.`title`->"$.fr")'), 'like', '%'.strtolower($searchQuery).'%')
                    ->orWhere(DB::raw('lower(`resources`.`summary`->"$.en")'), 'like', '%'.strtolower($searchQuery).'%')
                    ->orWhere(DB::raw('lower(`resources`.`summary`->"$.fr")'), 'like', '%'.strtolower($searchQuery).'%');
            })
                ->when($this->resourceTypes, function ($query, $resourceTypes) {
                    $query->whereResourceTypes($resourceTypes);
                })
                ->when($this->impacts, function ($query, $impacts) {
                    $query->whereImpacts($impacts);
                })
                ->when($this->languages, function ($query, $languages) {
                    $query->whereLanguages($languages);
                })
                ->when($this->phases, function ($query, $phases) {
                    $query->wherePhases($phases);
                })
                ->when($this->sectors, function ($query, $sectors) {
                    $query->whereSectors($sectors);
                })
                ->when($this->topics, function ($query, $topics) {
                    $query->whereTopics($topics);
                })
                ->with('topics', 'impacts', 'sectors')
                ->orderBy('created_at', 'desc')
                ->paginate(20, pageName: 'resources-page'),
            'resourceTypesData' => Options::forModels(ResourceType::class)->toArray(),
            'impactsData' => Options::forModels(Impact::class)->toArray(),
            'languagesData' => Options::forArray(get_available_languages())->toArray(),
            'phasesData' => Options::forEnum(ConsultationPhase::class)->toArray(),
            'sectorsData' => Options::forModels(Sector::class)->toArray(),
            'topicsData' => Options::forModels(Topic::class)->toArray(),
        ])
            ->layout('layouts.app', ['bodyClass' => 'page library', 'headerClass' => 'stack full pale', 'pageWidth' => 'wide']);
    }
}
