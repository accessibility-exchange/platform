<?php

use App\Enums\EngagementFormat;

dataset('browseEngagementsFormat', function () {
    $formats = array_column(EngagementFormat::cases(), 'value');
    $engagementNames = array_combine($formats, array_map(fn ($format) => "{$format} - Engagement", $formats));
    $testCases = [];

    $testCases['No Formats'] = [
        'filter' => [],
        'toSee' => $engagementNames,
    ];

    $testCases['All Formats'] = [
        'filter' => array_column(EngagementFormat::cases(), 'value'),
        'toSee' => $engagementNames,
    ];

    foreach ($engagementNames as $format => $engagementName) {
        $dontSee = [];

        foreach ($engagementNames as $otherFormat => $otherEngagementName) {
            if ($otherFormat !== $format) {
                $dontSee[$otherFormat] = $otherEngagementName;
            }
        }

        $testCases["Only {$format} format"] = [
            'filter' => [$format],
            'toSee' => [$format => $engagementName],
            'dontSee' => $dontSee,
        ];
    }

    return $testCases;
});
