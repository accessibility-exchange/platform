<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'general.email',
            fn (string $email) => [
                'en' => $email,
                'fr' => 'support@connecteuraccessibilite.ca',
            ]
        );
        $this->migrator->update(
            'general.phone',
            fn (string $phone) => ['en' => $phone]
        );
        $this->migrator->update(
            'general.email_privacy',
            fn (string $email_privacy) => ['en' => $email_privacy]
        );
        $this->migrator->update(
            'general.address',
            fn (string $address) => ['en' => $address]
        );
        $this->migrator->update(
            'general.facebook',
            fn (string $facebook) => ['en' => $facebook]
        );
        $this->migrator->update(
            'general.linkedin',
            fn (string $linkedin) => ['en' => $linkedin]
        );
        $this->migrator->update(
            'general.twitter',
            fn (string $twitter) => ['en' => $twitter]
        );
        $this->migrator->update(
            'general.youtube',
            fn (string $youtube) => ['en' => $youtube]
        );
    }

    public function down(): void
    {
        $this->migrator->update(
            'general.email',
            fn ($email) => $email->en
        );
        $this->migrator->update(
            'general.phone',
            fn ($phone) => $phone->en
        );
        $this->migrator->update(
            'general.email_privacy',
            fn ($email_privacy) => $email_privacy->en
        );
        $this->migrator->update(
            'general.address',
            fn ($address) => $address->en
        );
        $this->migrator->update(
            'general.facebook',
            fn ($facebook) => $facebook->en
        );
        $this->migrator->update(
            'general.linkedin',
            fn ($linkedin) => $linkedin->en
        );
        $this->migrator->update(
            'general.twitter',
            fn ($twitter) => $twitter->en
        );
        $this->migrator->update(
            'general.youtube',
            fn ($youtube) => $youtube->en
        );
    }
};
