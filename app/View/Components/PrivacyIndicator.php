<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PrivacyIndicator extends Component
{
    /**
     * The privacy level for the privacy indicator.
     *
     * @var string
     */
    public $level;

    /**
     * The text for the privacy indicator.
     *
     * @var null|string
     */
    public $value;

    /**
     * Create a new component instance.
     *
     * @param string $privacy The privacy level for the privacy indicator.
     * @param string $value The text for the privacy indicator.
     *
     * @return void
     */
    public function __construct($level = 'private', $value = null)
    {
        $this->level = $level;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.privacy-indicator');
    }
}
