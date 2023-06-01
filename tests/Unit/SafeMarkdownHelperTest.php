<?php

use Illuminate\Support\HtmlString;

test('html encode link replacements', function ($string, $expected) {
    expect(safe_link_replacement($string))->toEqual($expected);
})->with([
    'URL' => [
        'http://example.com?search=test&limit=5',
        '<a href="http://example.com?search=test&amp;limit=5">http://example.com?search=test&amp;limit=5</a>',
    ],
    'email' => [
        'test*_&1@example.com',
        '<a href="mailto:test*_&amp;1@example.com">test*_&amp;1@example.com</a>',
    ],
    'not an email or URL' => [
        '<strong>testing</strong>',
        '&lt;&lt;strong&gt;testing&lt;/strong&gt;&gt;',
    ],
]);

test('html entity encoding of string replacements', function ($args, $expected) {
    expect(call_user_func_array('html_replacements', $args))->toEqual($expected);
})->with([
    'no replacements' => [
        ['hello :world'],
        'hello :world',
    ],
    'has replacement' => [
        ['hello :world', ['world' => 'earth']],
        'hello earth',
    ],
    'escaped replacement' => [
        ['hello :world', ['world' => '<strong>earth</strong>']],
        'hello &lt;strong&gt;earth&lt;/strong&gt;',
    ],
    'escaped and unescaped replacement' => [
        ['hello :world; good morning :!world', ['world' => '<strong>earth</strong>']],
        'hello &lt;strong&gt;earth&lt;/strong&gt;; good morning <strong>earth</strong>',
    ],
    'replace URL' => [
        ['hello <:url>', ['url' => 'http://example.com?a=1&b=2']],
        'hello <a href="http://example.com?a=1&amp;b=2">http://example.com?a=1&amp;b=2</a>',
    ],
    'replace email' => [
        ['hello <:email>', ['email' => 'test_1&2@example.com']],
        'hello <a href="mailto:test_1&amp;2@example.com">test_1&amp;2@example.com</a>',
    ],
    'replace <:placeholder> without a URL or email' => [
        ['hello <:world>', ['world' => '<strong>earth</strong>']],
        'hello &lt;&lt;strong&gt;earth&lt;/strong&gt;&gt;',
    ],
    'replace entity encoded URL placeholder' => [
        ['hello &lt;:url&gt;', ['url' => 'http://example.com?a=1&b=2']],
        'hello <a href="http://example.com?a=1&amp;b=2">http://example.com?a=1&amp;b=2</a>',
    ],
    'string has html in it' => [
        ['hello <strong>:world</strong>', ['world' => 'earth']],
        'hello <strong>earth</strong>',
    ],
    'string has markdown in it' => [
        ['hello **:world**', ['world' => 'earth']],
        'hello **earth**',
    ],
]);

test('safe markdown conversion', function ($args, $expected) {
    $inlineMarkdownOutput = call_user_func_array('safe_inlineMarkdown', $args);
    expect($inlineMarkdownOutput)->toBeInstanceOf(HtmlString::class);
    expect(trim($inlineMarkdownOutput))->toEqual($expected);

    $markdownOutput = call_user_func_array('safe_markdown', $args);
    expect($inlineMarkdownOutput)->toBeInstanceOf(HtmlString::class);
    expect(trim($markdownOutput))->toEqual("<p>{$expected}</p>");
})->with([
    'accepted markdown syntax' => [
        ['_**NOTE:** Testing_'],
        '<em><strong>NOTE:</strong> Testing</em>',
    ],
    'escape HTML' => [
        ['Test <strong>HTML</strong> inline'],
        'Test &lt;strong&gt;HTML&lt;/strong&gt; inline',
    ],
    'remove unsafe links' => [
        ['Test [unsafe](javascript:alert("hello")) link'],
        'Test <a>unsafe</a> link',
    ],
    'markdown unprocessed in replacement' => [
        ['Injected :markdown', ['markdown' => '**[test](http://example.com)**']],
        'Injected **[test](http://example.com)**',
    ],
    'escaped html in replacement' => [
        ['Injected :markdown', ['markdown' => '<strong>should escape</strong>']],
        'Injected &lt;strong&gt;should escape&lt;/strong&gt;',
    ],
    'Unescaped replacement' => [
        ['Test <strong>:text</strong>; :!other', ['text' => '**content:** <em>escaped</em>', 'other' => '<span class="someClass">unescaped</span>']],
        'Test &lt;strong&gt;**content:** &lt;em&gt;escaped&lt;/em&gt;&lt;/strong&gt;; <span class="someClass">unescaped</span>',
    ],
    'Same replacement used as both escaped and unescaped' => [
        ['Escaped (:text); Unescaped (:!text)', ['text' => '**my** <em>link</em>']],
        'Escaped (**my** &lt;em&gt;link&lt;/em&gt;); Unescaped (**my** <em>link</em>)',
    ],
    'Replace URLs and Emails' => [
        ['<:name> email: <:email>; <:name> website: <:url>', ['name' => '**My**', 'email' => 'my*@example.com', 'url' => 'http://example.com/user=my&sort=asc']],
        '&lt;**My**&gt; email: <a href="mailto:my*@example.com">my*@example.com</a>; &lt;**My**&gt; website: <a href="http://example.com/user=my&amp;sort=asc">http://example.com/user=my&amp;sort=asc</a>',
    ],
]);
