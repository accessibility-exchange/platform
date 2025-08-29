<?php

use App\Enums\TimeZone;
use App\View\Components\TimeFrame;
use Carbon\Carbon;

test('timeframe within same year', function () {
    $start = new Carbon('first day of January 2023', TimeZone::Eastern->value);
    $end = new Carbon('first day of March 2023', TimeZone::Eastern->value);
    $view = $this->component(TimeFrame::class, ['start' => $start, 'end' => $end]);

    $view->assertSee('January&ndash;March 2023', false);
});

test('timeframe across years', function () {
    $start = new Carbon('first day of January 2022', TimeZone::Eastern->value);
    $end = new Carbon('first day of March 2023', TimeZone::Eastern->value);
    $view = $this->component(TimeFrame::class, ['start' => $start, 'end' => $end]);

    $view->assertSee('January 2022&ndash;March 2023', false);
});
