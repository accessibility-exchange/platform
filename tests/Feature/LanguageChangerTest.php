<?php

use App\Models\Individual;
use App\View\Components\LanguageChanger;

beforeEach(function () {
    $this->model = Individual::factory()->create([
        'bio' => [
            'en' => 'bio (en)',
            'fr' => 'bio (fr)',
            'es' => 'bio (es)',
        ],
        'languages' => ['en', 'fr', 'es'],
    ]);
});

test('example', function (string $appLocale, ?string $pageLocale) {
    App::setLocale($appLocale);

    $contentLocale = $pageLocale ?? $appLocale;
    $otherLocales = array_filter($this->model->languages, fn ($code) => $code !== $contentLocale);

    $view = $this->component(LanguageChanger::class, [
        'model' => $this->model,
        'currentLanguage' => $pageLocale,
    ]);

    $view->assertDontSee(get_language_exonym($contentLocale));

    foreach ($otherLocales as $locale) {
        $view->assertSee(get_language_exonym($locale));
    }
})->with([
    'English app locale' => 'en',
    'French app locale' => 'fr',
])->with([
    'English page locale' => 'en',
    'French page locale' => 'fr',
    'Spanish page locale' => 'es',
    'No page locale' => null,
]);
