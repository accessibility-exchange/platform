<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public string $bodyClass;

    public string $headerClass;

    public ?string $pageWidth;

    public function __construct(string $bodyClass = 'page', string $headerClass = 'stack', ?string $pageWidth = null)
    {
        $this->bodyClass = $bodyClass;
        $this->headerClass = $headerClass;
        $this->pageWidth = $pageWidth;
    }

    public function render(): View
    {
        return view('layouts.app');
    }
}
