<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class StoreRegistrationRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'password' => 'correctHorse-batteryStaple7',
            'password_confirmation' => 'correctHorse-batteryStaple7',
            'accepted_terms_of_service' => true,
            'accepted_privacy_policy' => true,
        ];
    }
}
