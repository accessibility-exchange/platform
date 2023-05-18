<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.individual_orientation', 'https://share.hsforms.com/161eyaBsQS-iv1z0TZLwdQwdfpez');
        $this->migrator->add('general.org_orientation', 'https://share.hsforms.com/1sB6UV4gvQlC_0QxQ3q3z1Adfpez');
        $this->migrator->add('general.fro_orientation', 'https://share.hsforms.com/1gGf9TjhaQ0uaqcnyJfSDlwdfpez');
        $this->migrator->add('general.ac_application', 'https://share.hsforms.com/16bxWtxLKQmCdjZo4TmJU9wdfpez');
        $this->migrator->add('general.cc_application', 'https://share.hsforms.com/1Pkmwsp2RT9mrvFh2d9iYfQdfpez');
    }
};
