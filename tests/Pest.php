<?php

use Illuminate\Foundation\Testing\TestCase;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Tests\CreatesApplication;
use Tests\DuskTestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(DuskTestCase::class)->in('Browser');
uses(TestCase::class, CreatesApplication::class, FastRefreshDatabase::class)->in('Feature');
uses(TestCase::class, CreatesApplication::class, FastRefreshDatabase::class)->in('Unit');

uses()->compact();

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/
