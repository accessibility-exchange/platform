<?php

dataset('addTranslationRequestValidationErrors', function () {
    return [
        'missing translatable type' => [
            ['translatable_type' => null],
        ],
        'translatable type not a string' => [
            ['translatable_type' => true],
        ],
        'translatable id invalid' => [
            ['translatable_id' => 1000000],
        ],
        'missing new language' => [
            ['new_language' => null],
            fn () => ['new_language' => __('Please select a language that :model will be translated to.', ['model' => 'Tester'])],
        ],
        'new language not a string' => [
            ['new_language' => 30],
            fn () => ['new_language' => __('Please select a language that :model will be translated to.', ['model' => 'Tester'])],
        ],
        'new language invalid' => [
            ['new_language' => 'en'],
            fn () => ['new_language' => __(':model is already translatable into :language.', ['model' => 'Tester', 'language' => get_language_exonym('en')])],
        ],
    ];
});
