<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * The alert type.
     *
     * @var string
     */
    public $type;

    /**
     * The alert icon.
     *
     * @var string
     */
    public $icon;

    /**
     * The alert title.
     *
     * @var string
     */
    public $title;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $type = "info")
    {
        $this->type = $type;

        switch ($type) {
            case "danger":
                $this->icon = "heroicon-s-x-circle";
                break;
            case "warning":
                $this->icon = "heroicon-s-exclamation";
                break;
            case "success":
                $this->icon = "heroicon-s-check-circle";
                break;
            case "info":
            default:
                $this->icon = "heroicon-s-information-circle";
        }

        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
