<?php

use App\Models\Individual;
use App\View\Components\LanguageChanger;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->individual = Individual::factory()->create([
        'bio' => [
            'en' => 'bio (en)',
            'fr' => 'bio (fr)',
            'es' => 'bio (es)',
        ],
        'languages' => ['en', 'fr', 'es'],
    ]);
});

test('language changer renders correct links', function (string $appLocale, ?string $pageLocale) {
    App::setLocale($appLocale);

    $contentLocale = $pageLocale ?? $appLocale;
    $otherLocales = array_filter($this->individual->languages, fn ($code) => to_written_language($code) !== to_written_language($contentLocale));
    $routeName = 'test-route';

    Route::multilingual('test/route/{individual}', function () use ($pageLocale) {
        return $this->component(LanguageChanger::class, [
            'model' => $this->individual,
            'currentLanguage' => $pageLocale,
        ]);
    }, config('locales.supported'))->name($routeName)->register();

    $url = localized_route($routeName, $this->individual);

    $view = get($url);

    $view->assertOk();
    $view->assertDontSee(get_language_exonym($contentLocale));

    foreach ($otherLocales as $locale) {
        $localeURL = in_array($locale, config('locales.supported')) ?
            Str::replaceFirst("/{$appLocale}/", "/{$locale}/", $url) :
            "{$url}?language={$locale}";

        $view->assertSeeInOrder([
            $localeURL,
            get_language_exonym($locale),
        ]);
    }
})->with('supportedLocales')->with([
    'English page locale' => 'en',
    'French page locale' => 'fr',
    'Spanish page locale' => 'es',
    'No page locale' => null,
]);
