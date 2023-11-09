<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

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
        $html = Str::markdown($content, config('markdown'));

        return new HtmlString(html_replacements($html, [
            'home' => config('app.url'),
            'email' => settings('email'),
            'privacy_policy' => localized_route('about.privacy-policy'),
            'tos' => localized_route('about.terms-of-service'),
        ]));
    }
}
