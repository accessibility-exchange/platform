<?php

it('can identify a signed language', function () {
    $this->assertTrue(is_signed_language('ase'));
    $this->assertFalse(is_signed_language('en'));
});
