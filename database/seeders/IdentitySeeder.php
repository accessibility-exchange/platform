<?php

namespace Database\Seeders;

use App\Enums\IdentityCluster;
use App\Models\Identity;
use Illuminate\Database\Seeder;

class IdentitySeeder extends Seeder
{
    public function run()
    {
        $identities = [
            [
                'name' => __('Children (under 15)'),
                'clusters' => [IdentityCluster::Age->value],
            ],
            [
                'name' => __('Youth (15–30)'),
                'clusters' => [IdentityCluster::Age->value],
            ],
            [
                'name' => __('Working age adults (15–64)'),
                'clusters' => [IdentityCluster::Age->value],
            ],
            [
                'name' => __('Older people (65+)'),
                'clusters' => [IdentityCluster::Age->value],
            ],
            [
                'name' => __('Urban areas'),
                'clusters' => [IdentityCluster::Area->value],
            ],
            [
                'name' => __('Rural areas'),
                'clusters' => [IdentityCluster::Area->value],
            ],
            [
                'name' => __('Remote areas'),
                'clusters' => [IdentityCluster::Area->value],
            ],
            [
                'name' => __('Refugees'),
                'clusters' => [IdentityCluster::Status->value],
            ],
            [
                'name' => __('Immigrants'),
                'clusters' => [IdentityCluster::Status->value],
            ],
            [
                'name' => __('Single parents and/or guardians'),
                'clusters' => [IdentityCluster::Family->value],
            ],
            [
                'name' => __('Trans people'),
                'clusters' => [IdentityCluster::GenderAndSexuality->value],
            ],
            [
                'name' => __('2SLGBTQIA+ people'),
                'clusters' => [IdentityCluster::GenderAndSexuality->value],
            ],
            [
                'name' => __('Visual disabilities'),
                'description' => __('Includes individuals with sight loss, blind individuals, and partially sighted individuals'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Deaf'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Hard-of-hearing'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Physical and mobility disabilities'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Pain-related disabilities'),
                'description' => __('Such as chronic fatigue syndrome'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Communication disabilities'),
                'description' => __('Includes individuals with no spoken or signed language who communicate using gestures, pictures, letter boards, communication devices or assistance from a person who knows them well'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Neurodivergence'),
                'description' => __('Such as Autism, ADHD'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Developmental disabilities'),
                'description' => __('Includes intellectual disability'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Learning disabilities'),
                'description' => __('Such as dyslexia'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Cognitive disabilities'),
                'description' => __('Includes traumatic brain injury, memory difficulties, dementia'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Mental health-related disabilities'),
                'description' => __('Such as dual diagnosis of a mental health barrier, substance dependence'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Multiple disabilities'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Episodic and invisible disabilities'),
                'description' => __('Such as environmental, HIV, migraine'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('DeafBlind'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Body differences'),
                'description' => __('Includes size, limb, and facial differences'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value],
            ],
            [
                'name' => __('Temporary disabilities'),
                'description' => __('Such as broken limbs, gestational diabetes'),
                'clusters' => [IdentityCluster::DisabilityAndDeaf->value, IdentityCluster::OnlyReachableWithinMixedGroups->value],
            ],
            [
                'name' => __('White'),
                'clusters' => [IdentityCluster::Ethnoracial->value, IdentityCluster::OnlyReachableWithinMixedGroups->value],
            ],
            [
                'name' => __('Black'),
                'clusters' => [IdentityCluster::Ethnoracial->value],
            ],
            [
                'name' => __('East Asian'),
                'clusters' => [IdentityCluster::Ethnoracial->value],
            ],
            [
                'name' => __('Asian'),
                'clusters' => [IdentityCluster::Ethnoracial->value],
            ],
            [
                'name' => __('South Asian'),
                'clusters' => [IdentityCluster::Ethnoracial->value],
            ],
            [
                'name' => __('Southeast Asian'),
                'clusters' => [IdentityCluster::Ethnoracial->value],
            ],
            [
                'name' => __('Middle Eastern'),
                'clusters' => [IdentityCluster::Ethnoracial->value],
            ],
            [
                'name' => __('Latin American'),
                'clusters' => [IdentityCluster::Ethnoracial->value],
            ],
            [
                'name' => __('African'),
                'clusters' => [IdentityCluster::Ethnoracial->value],
            ],
            [
                'name' => __('Women'),
                'clusters' => [IdentityCluster::Gender->value, IdentityCluster::GenderAndSexuality->value],
            ],
            [
                'name' => __('Men'),
                'clusters' => [IdentityCluster::Gender->value, IdentityCluster::GenderAndSexuality->value, IdentityCluster::OnlyReachableWithinMixedGroups->value],
            ],
            [
                'name' => __('Non-binary people'),
                'clusters' => [IdentityCluster::Gender->value, IdentityCluster::GenderAndSexuality->value, IdentityCluster::GenderDiverse->value],
            ],
            [
                'name' => __('Gender non-conforming people'),
                'clusters' => [IdentityCluster::Gender->value, IdentityCluster::GenderAndSexuality->value, IdentityCluster::GenderDiverse->value],
            ],
            [
                'name' => __('Gender fluid people'),
                'clusters' => [IdentityCluster::Gender->value, IdentityCluster::GenderAndSexuality->value, IdentityCluster::GenderDiverse->value],
            ],
            [
                'name' => __('First Nations'),
                'clusters' => [IdentityCluster::Indigenous->value],
            ],
            [
                'name' => __('Inuit'),
                'clusters' => [IdentityCluster::Indigenous->value],
            ],
            [
                'name' => __('Métis'),
                'clusters' => [IdentityCluster::Indigenous->value],
            ],
            [
                'name' => __('Supporters'),
                'clusters' => [IdentityCluster::LivedExperience->value, IdentityCluster::OnlyReachableWithinMixedGroups->value],
            ],
        ];

        foreach ($identities as $identity) {
            Identity::firstOrCreate([
                'name' => [
                    'en' => $identity['name'],
                    'fr' => trans($identity['name'], [], 'fr'),
                ],
                'description' => [
                    'en' => $identity['description'] ?? null,
                    'fr' => isset($identity['description']) ? trans($identity['description'], [], 'fr') : null,
                ],
                'clusters' => $identity['clusters'] ?? [],
            ]);
        }
    }
}
