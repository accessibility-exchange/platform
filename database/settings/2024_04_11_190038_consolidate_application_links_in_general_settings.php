<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->delete('general.ac_application');
        $this->migrator->delete('general.cc_application');
        $this->migrator->add('general.ac_cc_application', [
            'en' => 'https://share.hsforms.com/1UZjxoUCFRJmcnK8ULsPowwdfpez',
            'fr' => 'https://share.hsforms.com/1M0wCcgQwSQ27eBra_asFxwdfpez',
        ]);
    }
};
