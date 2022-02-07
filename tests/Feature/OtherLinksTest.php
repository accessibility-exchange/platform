<?php

namespace Tests\Feature;

use App\Http\Livewire\OtherLinks;
use Livewire\Livewire;
use Tests\TestCase;

class OtherLinksTest extends TestCase
{
    public function test_link_can_be_added(): void
    {
        Livewire::test(OtherLinks::class, ['links' => []])
            ->call('addLink')
            ->assertSet('links', [['title' => '', 'url' => '']]);
    }

    public function test_no_more_than_five_links_can_be_added(): void
    {
        Livewire::test(OtherLinks::class, ['links' => [
            ['title' => 'My blog 1', 'url' => 'https://my1st.blog'],
            ['title' => 'My blog 2', 'url' => 'https://my2nd.blog'],
            ['title' => 'My blog 3', 'url' => 'https://my3rd.blog'],
            ['title' => 'My blog 4', 'url' => 'https://my4th.blog'],
            ['title' => 'My blog 5', 'url' => 'https://my5th.blog'],
            ['title' => 'My blog 6', 'url' => 'https://my5th.blog'],
        ]])
            ->call('addLink')
            ->assertCount('links', 6);
    }

    public function test_link_can_be_removed(): void
    {
        Livewire::test(OtherLinks::class, ['links' => [['title' => 'My blog', 'url' => 'https://my.blog']]])
            ->call('removeLink', 0)
            ->assertSet('links', []);
    }
}
