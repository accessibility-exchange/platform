<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Traits\RendersSafeContent;
use Illuminate\Contracts\View\View;

class ToolController extends Controller
{
    use RendersSafeContent;

    public function index(): View
    {
        return view('tools.index', [
            'tools' => Tool::all(),
        ]);
    }

    public function show(Tool $tool): View
    {
        $tool->load('documents', 'revisions');

        return view('tools.show', [
            'tool' => $tool,
            'content' => $this->safeContent($tool->getTranslation('content', locale()) ?? ''),
        ]);
    }
}
