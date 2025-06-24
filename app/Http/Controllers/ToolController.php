<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class ToolController extends Controller
{
    public function index(): View
    {
        return view('tools.index', [
            'tools' => Tool::all(),
        ]);
    }

    public function show(Tool $tool): View
    {
        $tool->load('documents');

        return view('tools.show', [
            'tool' => $tool,
            'content' => $this->safeContent($tool->content),
        ]);
    }

    private function safeContent(string $content = ''): HtmlString
    {
        $config = array_merge([
            'heading_permalink' => [
                'id_prefix' => '',
                'apply_id_to_heading' => true,
                'fragment_prefix' => '',
                'insert' => 'none',
            ],
        ], config('markdown'));

        $converter = new GithubFlavoredMarkdownConverter($config);
        $environment = $converter->getEnvironment();

        $environment->addExtension(new HeadingPermalinkExtension);
        $environment->addExtension(new AttributesExtension);

        $html = $converter->convert($content);

        return new HtmlString($html);
    }
}
