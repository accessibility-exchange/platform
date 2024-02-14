<?php

namespace Tests\RequestFactories;

use App\Enums\BaseDisabilityType;
use App\Enums\IdentityCluster;
use App\Models\Identity;
use Worksome\RequestFactories\RequestFactory;

class UpdateOrganizationConstituenciesRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'disability_and_deaf' => true,
            'base_disability_type' => BaseDisabilityType::CrossDisability->value,
            'has_other_disability_constituency' => false,
            'has_indigenous_constituencies' => false,
            'refugees_and_immigrants' => false,
            'has_gender_and_sexuality_constituencies' => false,
            'has_age_bracket_constituencies' => false,
            'has_ethnoracial_identity_constituencies' => false,
            'has_other_ethnoracial_identity_constituency' => false,
            'area_type_constituencies' => Identity::query()->whereJsonContains('clusters', IdentityCluster::Area)->get()->modelKeys(),
            'staff_lived_experience' => 'prefer-not-to-answer',
        ];
    }
}
