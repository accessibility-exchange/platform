<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.vrs', [
            'en' => 'https://srvcanadavrs.ca/en/resources/resource-centre/vrs-basics/register/',
            'fr' => 'https://srvcanadavrs.ca/fr/resources/centre-de-ressources/introduction-au-srv/sinscrire/',
        ]);
    }
};
