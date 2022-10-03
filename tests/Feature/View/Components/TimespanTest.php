<?php

use App\View\Components\Timespan;
use Illuminate\Support\Carbon;

test('timespan component renders in expected format', function () {
    $start = new Carbon('2022-11-01T14:00:00', 'America/Toronto');
    $end = new Carbon('2022-11-01T15:00:00', 'America/Toronto');

    $view = $this->withViewErrors([])
        ->component(
            Timespan::class,
            ['start' => $start, 'end' => $end]
        );

    $view->assertSee('<time datetime="2022-11-01T14:00:00-04:00">Tuesday, November 1, 2022 2:00 PM</time>', false);
    $view->assertSee('<time datetime="2022-11-01T15:00:00-04:00">3:00 PM EDT</time>', false);
});
