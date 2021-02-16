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
        $this->regions = [
            'ab' => __('geography.ab'),
            'bc' => __('geography.bc'),
            'mb' => __('geography.mb'),
            'nb' => __('geography.nb'),
            'nl' => __('geography.nl'),
            'ns' => __('geography.ns'),
            'nt' => __('geography.nt'),
            'nu' => __('geography.nu'),
            'on' => __('geography.on'),
            'pe' => __('geography.pe'),
            'qc' => __('geography.qc'),
            'sk' => __('geography.sk'),
            'yt' => __('geography.yt')
        ];

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
