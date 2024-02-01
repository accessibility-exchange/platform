<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class PageController extends Controller
{
    public function showPage(Page $page): View
    {
        $content = $this->safeContent($page->getTranslation('content', locale()));

        return view('about.show-page', [
            'page' => $page,
            'content' => $content,
        ]);
    }

    public function showToS(): View
    {
        $page = Page::where('title->en', 'Terms of Service')->firstOrFail();

        return $this->showPage($page);
    }

    public function showPrivacyPolicy(): View
    {
        $page = Page::where('title->en', 'Privacy Policy')->firstOrFail();

        return $this->showPage($page);
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

        $environment->addExtension(new HeadingPermalinkExtension());
        $environment->addExtension(new AttributesExtension());

        $html = $converter->convert($content);

        return new HtmlString(html_replacements($html, [
            'home' => locale() === config('app.fallback_locale') ? config('app.url') : localized_route('welcome'),
            'email' => settings('email'),
            'email_privacy' => settings('email_privacy'),
            'privacy_policy' => localized_route('about.privacy-policy'),
            'tos' => localized_route('about.terms-of-service'),
        ]));
    }
}
