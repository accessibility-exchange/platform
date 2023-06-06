<?php

use App\Enums\IdentityCluster;
use App\Models\Identity;

dataset('matchingStrategyOtherIdentitiesSummary', function () {
    return [
        'no other identity' => [
            [],
            [],
            fn () => [__('Intersectional - This engagement is looking for people who have all sorts of different identities and lived experiences, such as race, gender, age, sexual orientation, and more.')],
        ],
        'with age-bracket identities' => [
            [
                'extra_attributes' => ['other_identity_type' => 'age-bracket'],
            ],
            fn () => [Identity::whereJsonContains('clusters', IdentityCluster::Age)->first()],
        ],
        'with gender-and-sexual-identity identities' => [
            [
                'extra_attributes' => ['other_identity_type' => 'gender-and-sexual-identity'],
            ],
            fn () => [Identity::whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->first()],
        ],
        'with indigenous-identity identities' => [
            [
                'extra_attributes' => ['other_identity_type' => 'indigenous-identity'],
            ],
            fn () => [Identity::whereJsonContains('clusters', IdentityCluster::Indigenous)->first()],
        ],
        'with ethnoracial-identity identities' => [
            [
                'extra_attributes' => ['other_identity_type' => 'ethnoracial-identity'],
            ],
            fn () => [Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->first()],
        ],
        'with refugee-or-immigrant identities' => [
            [
                'extra_attributes' => ['other_identity_type' => 'refugee-or-immigrant'],
            ],
            fn () => [Identity::whereJsonContains('clusters', IdentityCluster::Status)->first()],
        ],
        'with area-type identities' => [
            [
                'extra_attributes' => ['other_identity_type' => 'area-type'],
            ],
            fn () => [Identity::whereJsonContains('clusters', IdentityCluster::Area)->first()],
        ],
        'with first-language identities' => [
            [
                'extra_attributes' => ['other_identity_type' => 'first-language'],
            ],
            ['English'],
        ],
    ];
});
