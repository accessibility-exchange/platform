<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'general.individual_orientation',
            fn () => [
                'en' => 'https://share.hsforms.com/161eyaBsQS-iv1z0TZLwdQwdfpez',
                'fr' => 'https://share.hsforms.com/161eyaBsQS-iv1z0TZLwdQwdfpez?lang=fr',
            ]
        );
        $this->migrator->update('general.org_orientation', fn () => ['en' => 'https://share.hsforms.com/1sB6UV4gvQlC_0QxQ3q3z1Adfpez']);
        $this->migrator->update('general.fro_orientation', fn () => ['en' => 'https://share.hsforms.com/1gGf9TjhaQ0uaqcnyJfSDlwdfpez']);
        $this->migrator->update('general.ac_application', fn () => ['en' => 'https://share.hsforms.com/16bxWtxLKQmCdjZo4TmJU9wdfpez']);
        $this->migrator->update('general.cc_application', fn () => ['en' => 'https://share.hsforms.com/1Pkmwsp2RT9mrvFh2d9iYfQdfpez']);
    }
};
