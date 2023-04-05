<?php

namespace App\View\Components;

use App\Models\Interpretation as InterpretationModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Interpretation extends Component
{
    public string $id;

    public mixed $interpretation;

    public string $videoSrc;

    /**
     * The identifier name used to reference the Interpretation and videos.
     */
    public string $name;

    /**
     * The explicit namespace to organize the interpretation under
     */
    public ?string $namespace;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, ?string $namespace = null)
    {
        $this->name = $name;
        $this->namespace = $namespace;

        $this->interpretation = (is_signed_language(locale())) ?
            InterpretationModel::firstOrCreate(
                [
                    'name' => $this->name,
                    'namespace' => $this->namespace ?? Str::after(Route::currentRouteName(), locale().'.'),
                ],
                [
                    'route' => Str::after(Route::currentRouteName(), locale().'.'),
                    'route_has_params' => (bool) request()->route()->parameters(),
                ]
            ) :
            null;

        $this->id = Str::slug($this->interpretation?->name ?? $this->name);
        $this->videoSrc = $this->interpretation?->getTranslation('video', locale(), false) ?? '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.interpretation');
    }
}
