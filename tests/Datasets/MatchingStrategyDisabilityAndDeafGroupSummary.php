<?php

dataset('matchingStrategyDisabilityAndDeafGroupSummary', function () {
    return [
        'no disability and deaf group' => [
            [],
            false,
            fn () => [__('Cross disability (includes people with disabilities, Deaf people, and supporters)')],
        ],
        'cross disability and deaf' => [
            ['cross_disability_and_deaf' => 1],
            false,
            fn () => [__('Cross disability (includes people with disabilities, Deaf people, and supporters)')],
        ],
        'with disability identities' => [
            [],
            true,
        ],
        'with disability identities and cross disability and deaf' => [
            ['cross_disability_and_deaf' => 1],
            true,
        ],
    ];
});
