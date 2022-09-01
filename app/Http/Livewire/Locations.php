<?php

namespace App\Http\Livewire;

use App\Enums\ProvinceOrTerritory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Spatie\LaravelOptions\Options;

class Locations extends Component
{
    public string $name;

    public int $max;

    public array $regions = [];

    public array $locations = [];

    public function mount(array $locations = [], string $name = 'locations', int $max = 10)
    {
        $this->name = $name;
        $this->max = $max;
        $this->regions = Options::forEnum(ProvinceOrTerritory::class)->nullable(__('Choose a province or territory…'))->toArray();
        $this->locations = old($this->name, $locations);
    }

    public function addLocation(): void
    {
        if (! $this->canAddMoreLocations()) {
            return;
        }

        $this->locations[] = ['region' => '', 'locality' => ''];
    }

    public function removeLocation(int $i): void
    {
        unset($this->locations[$i]);

        $this->locations = array_values($this->locations);
    }

    public function canAddMoreLocations(): bool
    {
        return count($this->locations) < $this->max;
    }

    public function render(): View
    {
        return view('livewire.locations');
    }
}
