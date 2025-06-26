<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Traits\RendersSafeContent;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    use RendersSafeContent;

    public function showPage(Page $page): View
    {
        $content = $this->safeContent($page->getTranslation('content', locale() ?? ''), [
            'home' => locale() === config('app.fallback_locale') ? config('app.url') : localized_route('welcome'),
            'email' => settings_localized('email', locale()),
            'email_privacy' => settings_localized('email_privacy', locale()),
            'privacy_policy' => localized_route('about.privacy-policy'),
            'tos' => localized_route('about.terms-of-service'),
        ]);

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
}
