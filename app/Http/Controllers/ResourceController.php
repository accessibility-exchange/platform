<?php

namespace App\Http\Controllers;

use App\Enums\ConsultationPhase;
use App\Enums\ResourceFormat;
use App\Models\ContentType;
use App\Models\Resource;
use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Spatie\LaravelOptions\Options;

class ResourceController extends Controller
{
    public function index(): View
    {
        return view('resources.index', [
            'resources' => Resource::orderBy('title')->paginate(20),
            'topics' => Topic::all(),
            'types' => ContentType::all(),
            'formats' => Options::forEnum(ResourceFormat::class)->toArray(),
            'languages' => ['en', 'fr'],
            'phases' => Options::forEnum(ConsultationPhase::class)->toArray(),
        ]);
    }

    public function show(Resource $resource): View
    {
        return view('resources.show', ['resource' => $resource]);
    }
}
