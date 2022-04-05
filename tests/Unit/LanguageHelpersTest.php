<?php

namespace Tests\Unit;

use Tests\TestCase;

class LanguageHelpersTest extends TestCase
{
    public function test_is_signed_language()
    {
        $this->assertTrue(is_signed_language('ase'));
        $this->assertFalse(is_signed_language('en'));
    }
}
