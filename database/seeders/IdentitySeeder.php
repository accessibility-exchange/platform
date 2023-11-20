<?php

namespace Database\Seeders;

use App\Models\Identity;
use Illuminate\Database\Seeder;

class IdentitySeeder extends Seeder
{
    public function run()
    {
        $identities = [
            [
                'name' => __('Children (under 15)'),
                'clusters' => ['age'],
            ],
            [
                'name' => __('Youth (15–30)'),
                'clusters' => ['age'],
            ],
            [
                'name' => __('Working age adults (15–64)'),
                'clusters' => ['age'],
            ],
            [
                'name' => __('Older people (65+)'),
                'clusters' => ['age'],
            ],
            [
                'name' => __('Urban areas'),
                'clusters' => ['area'],
            ],
            [
                'name' => __('Rural areas'),
                'clusters' => ['area'],
            ],
            [
                'name' => __('Remote areas'),
                'clusters' => ['area'],
            ],
            [
                'name' => __('Refugees'),
                'clusters' => ['status'],
            ],
            [
                'name' => __('Immigrants'),
                'clusters' => ['status'],
            ],
            [
                'name' => __('Single parents and/or guardians'),
                'clusters' => ['family'],
            ],
            [
                'name' => __('Trans people'),
                'clusters' => ['gender-and-sexuality'],
            ],
            [
                'name' => __('2SLGBTQIA+ people'),
                'clusters' => ['gender-and-sexuality'],
            ],
            [
                'name' => __('Visual disabilities'),
                'description' => __('Includes individuals with sight loss, blind individuals, and partially sighted individuals'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Deaf'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Hard-of-hearing'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Physical and mobility disabilities'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Pain-related disabilities'),
                'description' => __('Such as chronic fatigue syndrome'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Communication disabilities'),
                'description' => __('Includes individuals with no spoken or signed language who communicate using gestures, pictures, letter boards, communication devices or assistance from a person who knows them well'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Neurodivergence'),
                'description' => __('Such as Autism, ADHD'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Developmental disabilities'),
                'description' => __('Includes intellectual disability'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Learning disabilities'),
                'description' => __('Such as dyslexia'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Cognitive disabilities'),
                'description' => __('Includes traumatic brain injury, memory difficulties, dementia'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Mental health-related disabilities'),
                'description' => __('Such as dual diagnosis of a mental health barrier, substance dependence'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Multiple disabilities'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Episodic and invisible disabilities'),
                'description' => __('Such as environmental, HIV, migraine'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('DeafBlind'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Body differences'),
                'description' => __('Includes size, limb, and facial differences'),
                'clusters' => ['disability-and-deaf'],
            ],
            [
                'name' => __('Temporary disabilities'),
                'description' => __('Such as broken limbs, gestational diabetes'),
                'clusters' => ['disability-and-deaf', 'reachable-when-mixed'],
            ],
            [
                'name' => __('White'),
                'clusters' => ['ethnoracial', 'reachable-when-mixed'],
            ],
            [
                'name' => __('Black'),
                'clusters' => ['ethnoracial'],
            ],
            [
                'name' => __('East Asian'),
                'clusters' => ['ethnoracial'],
            ],
            [
                'name' => __('Asian'),
                'clusters' => ['ethnoracial'],
            ],
            [
                'name' => __('South Asian'),
                'clusters' => ['ethnoracial'],
            ],
            [
                'name' => __('Southeast Asian'),
                'clusters' => ['ethnoracial'],
            ],
            [
                'name' => __('Middle Eastern'),
                'clusters' => ['ethnoracial'],
            ],
            [
                'name' => __('Latin American'),
                'clusters' => ['ethnoracial'],
            ],
            [
                'name' => __('African'),
                'clusters' => ['ethnoracial'],
            ],
            [
                'name' => __('Women'),
                'clusters' => ['gender', 'gender-and-sexuality'],
            ],
            [
                'name' => __('Men'),
                'clusters' => ['gender', 'gender-and-sexuality', 'reachable-when-mixed'],
            ],
            [
                'name' => __('Non-binary people'),
                'clusters' => ['gender', 'gender-and-sexuality', 'gender-diverse'],
            ],
            [
                'name' => __('Gender non-conforming people'),
                'clusters' => ['gender', 'gender-and-sexuality', 'gender-diverse'],
            ],
            [
                'name' => __('Gender fluid people'),
                'clusters' => ['gender', 'gender-and-sexuality', 'gender-diverse'],
            ],
            [
                'name' => __('First Nations'),
                'clusters' => ['indigenous'],
            ],
            [
                'name' => __('Inuit'),
                'clusters' => ['indigenous'],
            ],
            [
                'name' => __('Métis'),
                'clusters' => ['indigenous'],
            ],
            [
                'name' => __('Supporters'),
                'clusters' => ['lived-experience', 'reachable-when-mixed'],
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
