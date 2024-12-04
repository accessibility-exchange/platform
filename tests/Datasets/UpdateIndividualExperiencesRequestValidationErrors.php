<?php

dataset('updateIndividualExperiencesRequestValidationErrors', function () {
    return [
        'Lived experience is not an array' => [
            'state' => ['lived_experience' => 123],
            'errors' => fn () => ['lived_experience' => __('validation.array', ['attribute' => __('Lived experience')])],
        ],
        'Lived experience is invalid' => [
            'state' => ['lived_experience' => ['xx' => 'lived experience']],
            'errors' => fn () => ['lived_experience' => __('validation.array', ['attribute' => __('Lived experience')])],
        ],
        'Skills and strengths is not an array' => [
            'state' => ['skills_and_strengths' => 123],
            'errors' => fn () => ['skills_and_strengths' => __('validation.array', ['attribute' => __('Skills and strengths')])],
        ],
        'Skills and strengths is invalid' => [
            'state' => ['skills_and_strengths' => ['xx' => 'skills and strengths']],
            'errors' => fn () => ['skills_and_strengths' => __('validation.array', ['attribute' => __('Skills and strengths')])],
        ],
        'Relevant experience title is missing' => [
            'state' => ['relevant_experiences' => [
                [
                    'organization' => 'Example Org',
                    'start_year' => 2000,
                    'end_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.title' => __('validation.required_with', [
                'attribute' => __('Title of Role'),
                'values' => __('Name of Organization').' / '.__('Start Year').' / '.__('End Year').' / '.__('I currently work or volunteer here'),
            ])],
        ],
        'Relevant experience title is not a string' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 123,
                    'organization' => 'Example Org',
                    'start_year' => 2000,
                    'end_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.title' => __('validation.string', ['attribute' => __('Title of Role')])],
        ],
        'Relevant experience organization is missing' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'start_year' => 2000,
                    'end_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.organization' => __('validation.required_with', [
                'attribute' => __('Name of Organization'),
                'values' => __('Title of Role'),
            ])],
        ],
        'Relevant experience organization is not a string' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 123,
                    'start_year' => 2000,
                    'end_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.organization' => __('validation.string', ['attribute' => __('Name of Organization')])],
        ],
        'Relevant experience start year is missing' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'end_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.start_year' => __('validation.required_with', [
                'attribute' => __('Start Year'),
                'values' => __('Title of Role'),
            ])],
        ],
        'Relevant experience start year is not an integer' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 'test',
                    'end_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.start_year' => __('validation.integer', ['attribute' => __('Start Year')])],
        ],
        'Relevant experience start year is below min' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 1899,
                    'end_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.start_year' => __('validation.min.numeric', ['attribute' => __('Start Year'), 'min' => 1900])],
        ],
        'Relevant experience start year is above max' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => now()->addYear()->year,
                    'current' => true,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.start_year' => __('validation.max.numeric', ['attribute' => __('Start Year'), 'max' => now()->year])],
        ],
        'Relevant experience start year has too many digits' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => now()->timestamp,
                    'current' => true,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.start_year' => __('validation.digits', ['attribute' => __('Start Year'), 'digits' => 4])],
        ],
        'Relevant experience end year is missing' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.end_year' => __('validation.required_without', [
                'attribute' => __('End Year'),
                'values' => __('I currently work or volunteer here'),
            ])],
        ],
        'Relevant experience end year is prohibited with current year' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 2020,
                    'end_year' => now()->year,
                    'current' => true,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.end_year' => __('validation.prohibits', [
                'attribute' => __('End Year'),
                'other' => __('I currently work or volunteer here'),
            ])],
        ],
        'Relevant experience end year is not an integer' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 2000,
                    'end_year' => 'current',
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.end_year' => __('validation.integer', ['attribute' => __('End Year')])],
        ],
        'Relevant experience end year is before start year' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 2020,
                    'end_year' => 2002,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.end_year' => __('Please enter an end year for your experience that is equal to or greater than the start year.')],
        ],
        'Relevant experience end year is below min' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 1900,
                    'end_year' => 1899,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.end_year' => __('validation.min.numeric', ['attribute' => __('End Year'), 'min' => 1900])],
        ],
        'Relevant experience end year is above max' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 2020,
                    'end_year' => now()->addYear()->year,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.end_year' => __('validation.max.numeric', ['attribute' => __('End Year'), 'max' => now()->year])],
        ],
        'Relevant experience end year has too many digits' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 2020,
                    'end_year' => now()->timestamp,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.end_year' => __('validation.digits', ['attribute' => __('End Year'), 'digits' => 4])],
        ],
        'Relevant experience current is missing' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.current' => __('validation.required_without', [
                'attribute' => __('I currently work or volunteer here'),
                'values' => __('End Year'),
            ])],
        ],
        'Relevant experience current is not boolean' => [
            'state' => ['relevant_experiences' => [
                [
                    'title' => 'Example position',
                    'organization' => 'Example Org',
                    'start_year' => 2000,
                    'current' => 2020,
                ],
            ]],
            'errors' => fn () => ['relevant_experiences.0.current' => __('validation.boolean', ['attribute' => __('I currently work or volunteer here')])],
        ],
    ];
});
