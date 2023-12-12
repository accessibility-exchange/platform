<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class LanguageChanger extends Component
{
    public mixed $model;

    public string $currentLanguage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(mixed $model, ?string $currentLanguage)
    {
        $this->model = $model;
        $this->currentLanguage = $currentLanguage ?? locale();
    }

    public function getLanguageLink(string $locale, bool $asQuery = false): string
    {
        $route = request()->route();
        $params = array_merge(request()->except('language'), [Str::camel(class_basename($this->model)) => $this->model]);

        if ($asQuery) {
            return route($route->getName(), array_merge($params, ['language' => $locale]));
        }

        return localized_route_for_locale(route_name($route), $params, $locale);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.language-changer');
    }
}
