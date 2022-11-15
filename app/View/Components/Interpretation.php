<?php

namespace App\View\Components;

use App\Models\Interpretation as InterpretationModel;
use Illuminate\Support\Facades\Cookie;
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
     *
     * @var string
     */
    public string $name;

    /**
     * The explicit namespace to organize the interpretation under
     *
     * @var null|string
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

        $this->interpretation = (auth()->hasUser() && auth()->user()->sign_language_translations) || Cookie::get('sign_language_translations') ?
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
        $this->videoSrc = $this->interpretation?->getTranslation('video', get_signed_language_for_written_language(locale())) ?? '';
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
