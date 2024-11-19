<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'general.address',
            fn ($address) => array_map(fn ($value) => str_replace(' ℅ IRIS', '', $value), (array) $address)
        );
    }

    public function down(): void
    {
        $this->migrator->update(
            'general.address',
            fn ($address) => ['en' => 'The Accessibility Exchange ℅ IRIS
20-850 King Street West
Oshawa, ON, L1J 8N5']
        );
    }
};
