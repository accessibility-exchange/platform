<?php

use App\View\Components\Banner;

test('banner renders with appropriate icons', function () {
    $this->withViewErrors([])
        ->component(Banner::class)
        ->assertSee('class="banner banner--info"', false)
        ->assertSee('M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z', false);

    $this->withViewErrors([])
        ->component(
            Banner::class,
            ['type' => 'success']
        )
        ->assertSee('class="banner banner--success"', false)
        ->assertSee('M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', false);

    $this->withViewErrors([])
        ->component(
            Banner::class,
            ['type' => 'warning']
        )->assertSee('class="banner banner--warning"', false)
        ->assertSee('M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z', false);

    $this->withViewErrors([])
        ->component(
            Banner::class,
            ['type' => 'error']
        )->assertSee('class="banner banner--error"', false)
        ->assertSee('M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z', false);
});
