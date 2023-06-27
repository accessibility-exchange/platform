<?php

dataset('destroyTranslationRequestValidationErrors', function () {
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
        'missing language' => [
            ['language' => null],
            fn () => ['language' => __('Please select a language to remove.')],
        ],
        'language not a string' => [
            ['language' => 30],
            fn () => ['language' => __('Please select a language to remove.')],
        ],
        'language invalid' => [
            ['language' => 'es'],
            fn () => ['language' => __(':model was not translatable into :language.', ['model' => 'Tester', 'language' => get_language_exonym('es')])],
        ],
    ];
});
