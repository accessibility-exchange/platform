<?php

namespace Database\Seeders;

use App\Models\Identity;
use Illuminate\Database\Seeder;

class IdentitySeeder extends Seeder
{
    public function run()
    {
        $Identitys = [
            [
                'name' => __('Children (under 15)'),
                'cluster' => 'age',
            ],
            [
                'name' => __('Youth (15–30)'),
                'cluster' => 'age',
            ],
            [
                'name' => __('Working age adults (15–64)'),
                'cluster' => 'age',
            ],
            [
                'name' => __('Older people (65+)'),
                'cluster' => 'age',
            ],
            [
                'name' => __('Urban areas'),
                'cluster' => 'area',
            ],
            [
                'name' => __('Rural areas'),
                'cluster' => 'area',
            ],
            [
                'name' => __('Remote areas'),
                'cluster' => 'area',
            ],
            [
                'name' => __('Refugees and/or immigrants'),
            ],
            [
                'name' => __('Single parents and/or guardians'),
            ],
            [
                'name' => __('Trans people'),
            ],
            [
                'name' => __('2SLGBTQIA+ people'),
            ],
            [
                'name' => __('Cross-disability and Deaf'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Visual disabilities'),
                'description' => __('Includes individuals with sight loss, blind individuals, and partially sighted individuals'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Deaf'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Hard-of-hearing'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Physical and mobility disabilities'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Pain-related disabilities'),
                'description' => __('Such as chronic fatigue syndrome'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Communication disabilities'),
                'description' => __('Includes individuals with no spoken or signed language who communicate using gestures, pictures, letter boards, communication devices or assistance from a person who knows them well'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Neurodivergence'),
                'description' => __('Such as Autism, ADHD'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Developmental disabilities'),
                'description' => __('Includes intellectual disability'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Learning disabilities'),
                'description' => __('Such as dyslexia'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Cognitive disabilities'),
                'description' => __('Includes traumatic brain injury, memory difficulties, dementia'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Mental health-related disabilities'),
                'description' => __('Such as dual diagnosis of a mental health barrier, substance dependence'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Multiple disabilities'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Episodic and invisible disabilities'),
                'description' => __('Such as environmental, HIV, migraine'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('DeafBlind'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Body differences'),
                'description' => __('Includes size, limb, and facial differences'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('Temporary disabilities'),
                'description' => __('Such as broken limbs, gestational diabetes'),
                'cluster' => 'disability-and-deaf',
            ],
            [
                'name' => __('White'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('Black'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('East Asian'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('Asian'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('South Asian'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('Southeast Asian'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('Middle Eastern'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('Latin American'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('African'),
                'cluster' => 'ethnoracial',
            ],
            [
                'name' => __('Women'),
                'cluster' => 'gender',
            ],
            [
                'name' => __('Men'),
                'cluster' => 'gender',
            ],
            [
                'name' => __('Non-binary people'),
                'cluster' => 'gender',
            ],
            [
                'name' => __('Gender non-conforming people'),
                'cluster' => 'gender',
            ],
            [
                'name' => __('Gender fluid people'),
                'cluster' => 'gender',
            ],
            [
                'name' => __('First Nations'),
                'cluster' => 'indigenous',
            ],
            [
                'name' => __('Inuit'),
                'cluster' => 'indigenous',
            ],
            [
                'name' => __('Métis'),
                'cluster' => 'indigenous',
            ],
            [
                'name' => __('People with disabilities and/or Deaf people'),
                'cluster' => 'experience',
            ],
            [
                'name' => __('Supporters'),
                'cluster' => 'experience',
            ],
        ];

        foreach ($Identitys as $Identity) {
            Identity::firstOrCreate([
                'name->en' => $Identity['name'],
                'name->fr' => trans($Identity['name'], [], 'fr'),
                'description->en' => $Identity['description'] ?? null,
                'description->fr' => isset($Identity['description']) ? trans($Identity['description'], [], 'fr') : null,
                'cluster' => $Identity['cluster'] ?? null,
            ]);
        }
    }
}
