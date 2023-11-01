<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function showTos(): View
    {
        $page = Page::where('title->en', 'Terms of Service')->firstOrFail();
        $content = $this->safeContent($page->content);

        return view('about.terms-of-service', [
            'page' => $page,
            'content' => $content,
        ]);
    }

    public function showPrivacyPolicy(): View
    {
        $page = Page::where('title->en', 'Privacy Policy')->firstOrFail();
        $content = $this->safeContent($page->content);

        return view('about.privacy-policy', [
            'page' => $page,
            'content' => $content,
        ]);
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
