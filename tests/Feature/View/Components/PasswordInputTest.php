<?php

use App\View\Components\PasswordInput;

it('can be rendered', function () {
    $view = $this->withViewErrors([])
        ->component(
            PasswordInput::class,
            ['name' => 'password']
        );

    $view->assertSee('name="password"', false);
    $view->assertSee('id="password"', false);
});

it('can be rendered with custom id', function () {
    $view = $this->withViewErrors([])
        ->component(
            PasswordInput::class,
            [
                'name' => 'password',
                'id' => 'admin_password',
            ]
        );

    $view->assertSee('name="password"', false);
    $view->assertSee('id="admin_password"', false);
});

it('can be rendered with an error', function () {
    $view = $this->withViewErrors(['password' => 'You have entered an incorrect password.'])
        ->component(
            PasswordInput::class,
            [
                'name' => 'password',
            ]
        );

    $view->assertSee('aria-invalid', false);
});
