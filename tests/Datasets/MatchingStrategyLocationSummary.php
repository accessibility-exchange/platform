<?php

use App\Enums\ProvinceOrTerritory;

dataset('matchingStrategyLocationSummary', function () {
    return [
        'no region and location' => [
            [],
            fn () => [__('All provinces and territories')],
        ],
        'with locations' => [
            [
                'locations' => [
                    [
                        'region' => ProvinceOrTerritory::Ontario->value,
                        'locality' => 'Toronto',
                    ],
                ],
            ],
            fn () => [__(':locality, :region', ['locality' => 'Toronto', 'region' => ProvinceOrTerritory::labels()[ProvinceOrTerritory::Ontario->value]])],
        ],
        'with locations sorted' => [
            [
                'locations' => [
                    [
                        'region' => ProvinceOrTerritory::Ontario->value,
                        'locality' => 'Toronto',
                    ],
                    [
                        'region' => ProvinceOrTerritory::Alberta->value,
                        'locality' => 'Calgary',
                    ],
                ],
            ],
            fn () => [
                __(':locality, :region', ['locality' => 'Calgary', 'region' => ProvinceOrTerritory::labels()[ProvinceOrTerritory::Alberta->value]]),
                __(':locality, :region', ['locality' => 'Toronto', 'region' => ProvinceOrTerritory::labels()[ProvinceOrTerritory::Ontario->value]]),
            ],
        ],
        'with regions' => [
            ['regions' => [ProvinceOrTerritory::Ontario->value]],
            fn () => [ProvinceOrTerritory::labels()[ProvinceOrTerritory::Ontario->value]],
        ],
        'with regions sorted' => [
            [
                'regions' => [
                    ProvinceOrTerritory::Ontario->value,
                    ProvinceOrTerritory::Alberta->value,
                ],
            ],
            fn () => [
                ProvinceOrTerritory::labels()[ProvinceOrTerritory::Alberta->value],
                ProvinceOrTerritory::labels()[ProvinceOrTerritory::Ontario->value],
            ],
        ],
        'all provinces and territories specified' => [
            ['regions' => array_column(ProvinceOrTerritory::cases(), 'value')],
            ['All provinces and territories'],
        ],
        'with regions and locations' => [
            [
                'locations' => [
                    [
                        'region' => ProvinceOrTerritory::Ontario->value,
                        'locality' => 'Toronto',
                    ],
                ],
                'regions' => [ProvinceOrTerritory::Ontario->value],
            ],
            fn () => [ProvinceOrTerritory::labels()[ProvinceOrTerritory::Ontario->value]],
        ],
    ];
});
