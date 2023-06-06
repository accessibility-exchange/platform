<?php

use App\View\Components\TimeFrame;
use Carbon\Carbon;

test('timeframe within same year', function () {
    $start = new Carbon('first day of January 2023', 'America/Toronto');
    $end = new Carbon('first day of March 2023', 'America/Toronto');
    $view = $this->component(TimeFrame::class, ['start' => $start, 'end' => $end]);

    $view->assertSee('January&ndash;March 2023', false);
});

test('timeframe across years', function () {
    $start = new Carbon('first day of January 2022', 'America/Toronto');
    $end = new Carbon('first day of March 2023', 'America/Toronto');
    $view = $this->component(TimeFrame::class, ['start' => $start, 'end' => $end]);

    $view->assertSee('January 2022&ndash;March 2023', false);
});
