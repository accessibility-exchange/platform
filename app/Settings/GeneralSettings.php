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

    public array $individual_orientation;

    public array $org_orientation;

    public array $fro_orientation;

    public array $ac_application;

    public array $cc_application;

    public static function group(): string
    {
        return 'general';
    }
}
