<?php

namespace App\Traits;

use Illuminate\Support\HtmlString;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\GithubFlavoredMarkdownConverter;

trait RendersSafeContent
{
    private function safeContent(string $content = '', array $replacements = []): HtmlString
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

        return new HtmlString(html_replacements($html, $replacements));
    }
}
