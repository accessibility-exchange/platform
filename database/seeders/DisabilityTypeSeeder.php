<?php

namespace Database\Seeders;

use App\Models\DisabilityType;
use Illuminate\Database\Seeder;

class DisabilityTypeSeeder extends Seeder
{
    public function run(): void
    {
        $disabilityTypes = [
            [
                'name' => __('Visual disabilities'),
                'description' => __('Includes individuals with sight loss, blind individuals, and partially sighted individuals'),
            ],
            [
                'name' => __('Hard-of-hearing'),
            ],
            [
                'name' => __('Physical and mobility disabilities'),
            ],
            [
                'name' => __('Pain-related disabilities'),
                'description' => __('Such as chronic fatigue syndrome'),
            ],
            [
                'name' => __('Communication disabilities'),
                'description' => __('Includes individuals with no spoken or signed language who communicate using gestures, pictures, letter boards, communication devices or assistance from a person who knows them well'),
            ],
            [
                'name' => __('Neurodivergence'),
                'description' => __('Such as Autism, ADHD'),
            ],
            [
                'name' => __('Developmental disabilities'),
                'description' => __('Includes intellectual disability'),
            ],
            [
                'name' => __('Learning disabilities'),
                'description' => __('Such as dyslexia'),
            ],
            [
                'name' => __('Cognitive disabilities'),
                'description' => __('Includes traumatic brain injury, memory difficulties, dementia'),
            ],
            [
                'name' => __('Mental health-related disabilities'),
                'description' => __('Such as dual diagnosis of a mental health barrier, substance dependence'),
            ],
            [
                'name' => __('Multiple disabilities'),
            ],
            [
                'name' => __('Episodic and invisible disabilities'),
                'description' => __('Such as environmental, HIV, migraine'),
            ],
            [
                'name' => __('DeafBlind'),
            ],
            [
                'name' => __('Body differences'),
                'description' => __('Includes size, limb, and facial differences'),
            ],
            [
                'name' => __('Temporary disabilities'),
                'description' => __('Such as broken limbs, gestational diabetes'),
            ],
            [
                'name' => __('Other'),
            ],
        ];

        foreach ($disabilityTypes as $type) {
            DisabilityType::firstOrCreate([
                'name->en' => $type['name'],
                'name->fr' => trans($type['name'], [], 'fr'),
                'description->en' => $type['description'] ?? null,
                'description->fr' => isset($type['description']) ? trans($type['description'], [], 'fr') : null,
            ]);
        }
    }
}
