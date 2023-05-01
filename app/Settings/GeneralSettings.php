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

    public string $individual_orientation;

    public string $org_orientation;

    public string $fro_orientation;

    public string $ac_application;

    public string $cc_application;

    public static function group(): string
    {
        return 'general';
    }
}
