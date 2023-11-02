<?php

use Illuminate\Support\HtmlString;

test('encoding of N2BL content', function (bool $use_xhmtl, string $input, string $output) {
    $rendered = safe_nl2br($input, $use_xhmtl);
    $expected = str_replace('%break%', $use_xhmtl ? '<br />' : '<br>', $output);

    expect($rendered)->toBeInstanceOf(HtmlString::class);
    expect(trim($rendered))->toEqual($expected);
})->with([
    'xhtml compatible breaks' => true,
    'xhmtl incompatible breaks' => false,
])->with([
    'plain string' => [
        'input' => 'Text',
        'output' => 'Text',
    ],
    'new line' => [
        'input' => 'Before
                    After',
        'output' => 'Before%break%
                    After',
    ],
    'with HTML' => [
        'input' => '<strong>Before</strong>
                    After',
        'output' => '&lt;strong&gt;Before&lt;/strong&gt;%break%
                    After',
    ],
]);
