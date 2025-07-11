<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'general.address',
            fn ($address) => [
                'en' => 'The Accessibility Exchange
20-850 King Street West
Oshawa, ON, L1J 8N5',
                'fr' => 'Le Connecteur pour accessibilité
20-850 rue King Ouest
Oshawa, ON, L1J 8N5',
            ]
        );
    }
};
