<?php

use App\View\Components\Banner;

test('banner renders with appropriate icons', function () {
    $view = $this->withViewErrors([])
    ->component(Banner::class);

    $view->assertSee('class="banner banner--info"', false);
    $view->assertSee('d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"', false);

    $view = $this->withViewErrors([])
        ->component(
            Banner::class,
            ['type' => 'success']
        );

    $view->assertSee('class="banner banner--success"', false);
    $view->assertSee('d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"', false);

    $view = $this->withViewErrors([])
        ->component(
            Banner::class,
            ['type' => 'warning']
        );

    $view->assertSee('class="banner banner--warning"', false);
    $view->assertSee('d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"', false);

    $view = $this->withViewErrors([])
        ->component(
            Banner::class,
            ['type' => 'warning', 'icon' => 'heroicon-o-hand']
        );

    $view->assertSee('class="banner banner--warning"', false);
    $view->assertSee('d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"', false);
});
