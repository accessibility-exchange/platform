<?php

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Sushi\Sushi;

class TestModel extends Model
{
    use HasTranslations;

    // Sushi allows for models that use an array instead of a DB.
    use Sushi;

    protected $rows = [];

    protected $schema = [
        'prop' => 'string',
    ];

    protected $fillable = [
        'prop',
    ];

    protected $casts = [
        'prop' => 'array',
    ];

    public array $translatable = [
        'prop',
    ];
}

test('Translation fallback', function (array $translations, string $requestedLocale, string $expectedTranslation) {
    $model = TestModel::create([
        'prop' => $translations,
    ]);

    expect($model->getTranslation('prop', $requestedLocale))->toBe($expectedTranslation);

    App::setLocale($requestedLocale);

    expect($model->prop)->toBe($expectedTranslation);
})->with([
    'No ASL; fallback to EN' => [['en' => 'test'], 'asl', 'test'],
    'No LSQ; fallback to FR' => [['fr' => 'teste'], 'lsq', 'teste'],
    'No FR; fallback to EN' => [['en' => 'test'], 'fr', 'test'],
    'No LSQ and FR, fallback to EN' => [['en' => 'test'], 'lsq', 'test'],
    'Has ASL; no fallback' => [['asl' => 'test', 'en' => 'don’t fallback'], 'asl', 'test'],
    'Has LSQ; no fallback' => [['lsq' => 'teste', 'fr' => 'don’t fallback'], 'lsq', 'teste'],
    'Has FR; no fallback' => [['fr' => 'teste', 'en' => 'don’t fallback'], 'fr', 'teste'],
    'Has EN; no fallback' => [['fr' => 'teste', 'en' => 'test'], 'asl', 'test'],
]);
