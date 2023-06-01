<?php

test('Array list with a single item', function () {
    $view = $this->blade(
        '<x-array-list-view :data="$data" />',
        [
            'data' => ['Test'],
        ]
    );

    $view->assertSee('<p>Test</p>', false);

    $view->assertDontSee('<ul>', false);
    $view->assertDontSee('<li>', false);
});

test('Array list with multiple items', function () {
    $view = $this->blade(
        '<x-array-list-view :data="$data" />',
        [
            'data' => ['Item 1', 'Item 2'],
        ]
    );

    $view->assertSeeInOrder([
        '<ul role="list">',
        '<li>Item 1</li>',
        '<li>Item 2</li>',
        '</ul>',
    ], false);

    $view->assertDontSee('<p>', false);
});
