<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

class Timespan extends Component
{
    public string $display_start;

    public string $datetime_start;

    public string $display_end;

    public string $datetime_end;

    public function __construct(Carbon $start, Carbon $end)
    {
        $this->display_start = $start->isoFormat('LLLL');
        $this->datetime_start = $start->toIso8601String();
        $this->display_end = $end->isoFormat('LT z');
        $this->datetime_end = $start->toIso8601String();
    }

    public function render(): View
    {
        return view('components.timespan');
    }
}
