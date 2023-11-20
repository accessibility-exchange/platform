<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->delete('general.individual_orientation');
        $this->migrator->delete('general.org_orientation');
        $this->migrator->delete('general.fro_orientation');
        $this->migrator->delete('general.ac_application');
        $this->migrator->delete('general.cc_application');
    }
};
