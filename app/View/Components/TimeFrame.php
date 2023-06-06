<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TimeFrame extends Component
{
    public string $start;

    public string $end;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($start, $end)
    {
        $this->start = $start->translatedFormat($start->year === $end->year ? 'F' : 'F Y');
        $this->end = $end->translatedFormat('F Y');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.time-frame');
    }
}
