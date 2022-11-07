<?php

namespace App\View\Components;

use App\Models\Interpretation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Interpretable extends Component
{
    public string $id;

    public mixed $interpretation;

    public string $videoSrc;

    /**
     * The identifier name used to reference the Interpretable and videos.
     *
     * @var string
     */
    public string $name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $this->interpretation = Interpretation::firstOrCreate(
            [
                'route' => Str::after(Route::currentRouteName(), locale().'.'),
                'name' => $this->name,
            ],
            [
                'route_has_params' => (bool) request()->route()->parameters(),
            ]
        );

        $this->id = Str::slug($this->interpretation->name);
        $this->videoSrc = $this->interpretation->getTranslation('video', get_signed_language_for_written_language(locale()));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.interpretable');
    }
}
