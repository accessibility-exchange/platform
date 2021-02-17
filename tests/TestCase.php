<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function refreshApplicationWithLocale($locale)
    {
        self::tearDown();
        putenv(LaravelLocalization::ENV_ROUTE_KEY . '=' . $locale);
        self::setUp();
    }

    protected function tearDown()
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDown();
    }
}
