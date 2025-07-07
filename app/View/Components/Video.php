<?php

namespace App\View\Components;

use App\Models\Video as VideoModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Video extends Component
{
    public string $id;

    public VideoModel $video;

    public string $videoSrc;

    /**
     * The identifier name used to reference the videos.
     */
    public string $name;

    /**
     * The explicit namespace to organize the video under
     */
    public ?string $namespace;

    public function __construct(string $name, ?string $namespace = null)
    {
        $this->name = $name;
        $this->namespace = $namespace;

        $this->video = VideoModel::firstOrCreate(
            [
                'name' => $this->name,
                'namespace' => $this->namespace ?? Str::after(Route::currentRouteName(), locale().'.'),
            ],
            [
                'route' => Str::after(Route::currentRouteName(), locale().'.'),
            ]
        );

        $this->id = Str::slug($this->video->name ?? $this->name);
        $this->videoSrc = $this->video->getTranslation('video', locale(), false) ?? '';
    }

    public function render(): View|Closure|string
    {
        return view('components.video');
    }
}
