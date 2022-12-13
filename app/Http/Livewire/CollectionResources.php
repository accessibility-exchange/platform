<?php

namespace App\Http\Livewire;

use App\Enums\ConsultationPhase;
use App\Models\ContentType;
use App\Models\Impact;
use App\Models\ResourceCollection;
use App\Models\Sector;
use App\Models\Topic;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\LaravelOptions\Options;

class CollectionResources extends Component
{
    use WithPagination;

    public ResourceCollection $resourceCollection;

    public string $searchQuery = '';

    public array $contentTypes = [];

    public array $impacts = [];

    public array $languages = [];

    public array $phases = [];

    public array $sectors = [];

    public array $topics = [];

    protected $queryString = ['searchQuery' => ['except' => '', 'as' => 'search']];

    public function __invoke(Container $container, Route $route, ?ResourceCollection $resourceCollection = null)
    {
        return parent::__invoke($container, $route);
    }

    public function selectNone()
    {
        $this->contentTypes = [];
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
        return view('livewire.collection-resources', [
            'resources' => $this->resourceCollection->resources()->when($this->searchQuery, function ($query, $searchQuery) {
                $query->where('title->en', 'like', '%'.$searchQuery.'%')
                    ->orWhere('title->fr', 'like', '%'.$searchQuery.'%');
            })
                ->when($this->contentTypes, function ($query, $contentTypes) {
                    $query->whereContentTypes($contentTypes);
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
                ->paginate(20),
            'contentTypesData' => Options::forModels(ContentType::class)->toArray(),
            'impactsData' => Options::forModels(Impact::class)->toArray(),
            'languagesData' => Options::forArray(get_available_languages())->toArray(),
            'phasesData' => Options::forEnum(ConsultationPhase::class)->toArray(),
            'sectorsData' => Options::forModels(Sector::class)->toArray(),
            'topicsData' => Options::forModels(Topic::class)->toArray(),
        ])
            ->layout('layouts.app', ['bodyClass' => 'page resource-collection', 'headerClass' => 'stack', 'pageWidth' => 'wide']);
    }
}
