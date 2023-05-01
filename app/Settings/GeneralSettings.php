<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $email;

    public string $phone;

    public string $address;

    public string $facebook;

    public string $linkedin;

    public string $twitter;

    public string $youtube;

    public static function group(): string
    {
        return 'general';
    }
}
