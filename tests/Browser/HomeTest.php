<?php

uses(\Tests\DuskTestCase::class);
use Laravel\Dusk\Browser;

test('basic example', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/en/')
            ->assertSee('The Accessibility Exchange');
    });
});
