<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public array $email;

    public array $email_privacy;

    public array $phone;

    public array $address;

    public array $facebook;

    public array $linkedin;

    public array $twitter;

    public array $youtube;

    public array $individual_orientation;

    public array $org_orientation;

    public array $fro_orientation;

    public array $ac_cc_application;

    public static function group(): string
    {
        return 'general';
    }
}
