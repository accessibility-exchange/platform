<?php

namespace App\Http\Livewire;

use App\Models\Resource;
use App\Models\ResourceCollection;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ResourceSelect extends Component
{
    public Collection $availableResources;

    public Collection $selectedResources;

    public string $message = '';

    public function mount(?int $resourceCollectionId)
    {
        $this->availableResources = new Collection();
        $this->selectedResources = new Collection();
        if ($resourceCollectionId != null) {
            $resourcesInCollection = ResourceCollection::find($resourceCollectionId)->resources()->get();
            $this->availableResources = Resource::whereNotIn('id', $resourcesInCollection->pluck('id'))->get();
            $this->selectedResources = $resourcesInCollection;
        } else {
            $this->availableResources = Resource::orderBy('title')->get();
        }
    }

    public function addResource(int $i): void
    {
        $resourceToAdd = $this->availableResources->offsetGet($i);
        if ($resourceToAdd) {
            $this->selectedResources->push($resourceToAdd);
            $this->availableResources->splice($i, 1);
            $this->message = __('Resource ":resource" added to collection.', ['resource' => $resourceToAdd->title]);
        }
    }

    public function removeResource(int $i): void
    {
        $resourceToRemove = $this->selectedResources->offsetGet($i);
        if ($resourceToRemove) {
            $this->availableResources->push($resourceToRemove);
            $this->selectedResources->splice($i, 1);
            $this->message = __('Resource ":resource" removed from collection.', ['resource' => $resourceToRemove->title]);
        }
    }

    public function render()
    {
        return view('livewire.resource-select');
    }
}
