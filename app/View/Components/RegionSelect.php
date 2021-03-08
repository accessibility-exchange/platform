<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RegionSelect extends Component
{
    /**
     * The list of regions.
     *
     * @var array
     */
    public $regions;

    /**
     * The selected region.
     *
     * @var string
     */
    public $selected;

    /**
     * Create the component instance.
     *
     * @param  string  $selected
     * @return void
     */
    public function __construct($selected = "")
    {
        $this->regions = config('regions');

        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.region-select');
    }
}
