<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.email', 'support@accessibilityexchange.ca');
        $this->migrator->add('general.phone', '+1-888-867-0053');
        $this->migrator->add('general.address',
            'The Accessibility Exchange ℅ IRIS
20-850 King Street West
Oshawa, ON, L1J 8N5');
        $this->migrator->add('general.facebook', 'https://facebook.com/AccessXchange');
        $this->migrator->add('general.linkedin', 'https://linkedin.com/company/the-accessibility-exchange/');
        $this->migrator->add('general.twitter', 'https://twitter.com/AccessXchange');
        $this->migrator->add('general.youtube', 'https://www.youtube.com/channel/UC-mIk4Xk04wF4urFSKZQOAA');
    }
};
