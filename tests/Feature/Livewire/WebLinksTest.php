<?php

use App\Http\Livewire\WebLinks;

test('link can be added', function () {
    $this->livewire(WebLinks::class, ['links' => []])
        ->call('addLink')
        ->assertSet('links', [['title' => '', 'url' => '']]);
});

test('no more than five links can be added', function () {
    $this->livewire(WebLinks::class, ['links' => [
        ['title' => 'My blog 1', 'url' => 'https://my1st.blog'],
        ['title' => 'My blog 2', 'url' => 'https://my2nd.blog'],
        ['title' => 'My blog 3', 'url' => 'https://my3rd.blog'],
        ['title' => 'My blog 4', 'url' => 'https://my4th.blog'],
        ['title' => 'My blog 5', 'url' => 'https://my5th.blog'],
        ['title' => 'My blog 6', 'url' => 'https://my5th.blog'],
    ]])
        ->call('addLink')
        ->assertCount('links', 6);
});

test('link can be removed', function () {
    $this->livewire(WebLinks::class, ['links' => [['title' => 'My blog', 'url' => 'https://my.blog']]])
        ->call('removeLink', 0)
        ->assertSet('links', []);
});
