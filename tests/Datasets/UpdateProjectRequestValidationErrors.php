<?php

use Carbon\Carbon;

dataset('updateProjectRequestValidationErrors', function () {
    return [
        'Name is missing' => [
            'state' => ['name' => null],
            'errors' => fn () => [
                'name.en' => __('A project name must be provided in at least one language.'),
                'name.fr' => __('A project name must be provided in at least one language.'),
            ],
        ],
        'Name is missing required translation' => [
            'state' => ['name' => ['es' => 'Nombre del proyecto']],
            'errors' => fn () => [
                'name.en' => __('A project name must be provided in at least one language.'),
                'name.fr' => __('A project name must be provided in at least one language.'),
            ],
            'without' => ['name'],
        ],
        'Name translation is not a string' => [
            'state' => ['name.en' => false],
            'errors' => fn () => ['name.en' => __('validation.string', ['attribute' => __('Project name (English)')])],
        ],
        'Goal is missing' => [
            'state' => ['goals' => null],
            'errors' => fn () => [
                'goals.en' => __('Project goals must be provided in at least one language.'),
                'goals.fr' => __('Project goals must be provided in at least one language.'),
            ],
        ],
        'Goal is missing required translation' => [
            'state' => ['goals' => ['es' => 'Objetivos del proyecto']],
            'errors' => fn () => [
                'goals.en' => __('Project goals must be provided in at least one language.'),
                'goals.fr' => __('Project goals must be provided in at least one language.'),
            ],
            'without' => ['goals'],
        ],
        'Goal translation is not a string' => [
            'state' => ['goals.en' => false],
            'errors' => fn () => ['goals.en' => __('validation.string', ['attribute' => __('Project goals (English)')])],
        ],
        'Scope is missing' => [
            'state' => ['scope' => null],
            'errors' => fn () => [
                'scope.en' => __('Project scope must be provided in at least one language.'),
                'scope.fr' => __('Project scope must be provided in at least one language.'),
            ],
        ],
        'Scope is missing required translation' => [
            'state' => ['scope' => ['es' => 'Objetivos del proyecto']],
            'errors' => fn () => [
                'scope.en' => __('Project scope must be provided in at least one language.'),
                'scope.fr' => __('Project scope must be provided in at least one language.'),
            ],
            'without' => ['scope'],
        ],
        'Scope translation is not a string' => [
            'state' => ['scope.en' => false],
            'errors' => fn () => ['scope.en' => __('validation.string', ['attribute' => __('Project scope (English)')])],
        ],
        'Region is missing' => [
            'state' => ['regions' => null],
            'errors' => fn () => ['regions' => __('validation.required', ['attribute' => __('geographic areas')])],
        ],
        'Region is not a valid province or territory' => [
            'state' => ['regions' => ['XX']],
            'errors' => fn () => ['regions.0' => __('validation.exists', ['attribute' => __('geographic areas')])],
        ],
        'Impacts is missing' => [
            'state' => ['impacts' => null],
            'errors' => fn () => ['impacts' => __('validation.required', ['attribute' => __('areas of impact')])],
        ],
        'Impacts not an array' => [
            'state' => ['impacts' => 1000000],
            'errors' => fn () => ['impacts' => __('validation.array', ['attribute' => __('areas of impact')])],
        ],
        'Impact does not exist' => [
            'state' => ['impacts' => [1000000]],
            'errors' => fn () => ['impacts.0' => __('validation.exists', ['attribute' => __('areas of impact')])],
        ],
        'Out of scope not an array' => [
            'state' => ['out_of_scope' => 'out of scope'],
            'errors' => fn () => ['out_of_scope' => __('validation.array', ['attribute' => __('out of scope')])],
        ],
        'Out of scope message not a string' => [
            'state' => ['out_of_scope.en' => false],
            'errors' => fn () => ['out_of_scope.en' => __('validation.string', ['attribute' => __('out of scope')])],
        ],
        'Start Date is missing' => [
            'state' => ['start_date' => null],
            'errors' => fn () => ['start_date' => __('validation.required', ['attribute' => __('start date')])],
        ],
        'Start Date after End Date' => [
            'state' => fn () => [
                'start_date' => Carbon::now()->addMonth(),
                'end_date' => Carbon::now()->subMonth(),
            ],
            'errors' => fn () => [
                'start_date' => __('validation.before', ['attribute' => __('start date'), 'date' => __('end date')]),
                'end_date' => __('validation.after', ['attribute' => __('end date'), 'date' => __('start date')]),
            ],
        ],
        'End Date is missing' => [
            'state' => ['end_date' => null],
            'errors' => fn () => ['end_date' => __('validation.required', ['attribute' => __('end date')])],
        ],

        'Outcome analysis is missing' => [
            'state' => ['outcome_analysis' => null],
            'errors' => fn () => [
                'outcome_analysis' => __('You must identify who will be going through the results and producing an outcome.'),
                'has_other_outcome_analysis' => __('You must identify who will be going through the results and producing an outcome.'),
            ],
        ],
        'Outcome analysis translation is not a string' => [
            'state' => ['outcome_analysis.en' => false],
            'errors' => fn () => ['outcome_analysis.en' => __('validation.string', ['attribute' => __('Outcomes and reports')])],
        ],
        'Outcome analysis translation is invalid' => [
            'state' => ['outcome_analysis.en' => 'outsourced'],
            'errors' => fn () => ['outcome_analysis.en' => __('validation.exists', ['attribute' => __('Outcomes and reports')])],
        ],
        'Other outcome analysis translation is missing' => [
            'state' => ['has_other_outcome_analysis' => true],
            'errors' => fn () => [
                'outcome_analysis_other.en' => __('You must identify the other team that will be going through the results and producing an outcome.'),
                'outcome_analysis_other.fr' => __('You must identify the other team that will be going through the results and producing an outcome.'),
            ],
            'without' => ['outcome_analysis_other'],
        ],
        'Other outcome analysis translation is not a string' => [
            'state' => ['outcome_analysis_other.en' => 123, 'has_other_outcome_analysis' => true],
            'errors' => fn () => ['outcome_analysis_other.en' => __('validation.string', ['attribute' => __('Outcomes and reports other (English)')])],
        ],

        'Outcome is missing' => [
            'state' => ['outcomes' => null],
            'errors' => fn () => [
                'outcomes.en' => __('Tangible outcomes must be provided in at least one language.'),
                'outcomes.fr' => __('Tangible outcomes must be provided in at least one language.'),
            ],
        ],
        'Outcome is missing required translation' => [
            'state' => ['outcomes' => ['es' => 'Resultados del proyecto']],
            'errors' => fn () => [
                'outcomes.en' => __('Tangible outcomes must be provided in at least one language.'),
                'outcomes.fr' => __('Tangible outcomes must be provided in at least one language.'),
            ],
            'without' => ['outcomes'],
        ],
        'Outcome translation is not a string' => [
            'state' => ['outcomes.en' => false],
            'errors' => fn () => ['outcomes.en' => __('validation.string', ['attribute' => __('Project outcome (English)')])],
        ],
        'Outcome is missing' => [
            'state' => ['outcomes' => null],
            'errors' => fn () => [
                'outcomes.en' => __('Tangible outcomes must be provided in at least one language.'),
                'outcomes.fr' => __('Tangible outcomes must be provided in at least one language.'),
            ],
        ],
        'Outcome is missing required translation' => [
            'state' => ['outcomes' => ['es' => 'Resultados del proyecto']],
            'errors' => fn () => [
                'outcomes.en' => __('Tangible outcomes must be provided in at least one language.'),
                'outcomes.fr' => __('Tangible outcomes must be provided in at least one language.'),
            ],
            'without' => ['outcomes'],
        ],
        'Outcome translation is not a string' => [
            'state' => ['outcomes.en' => false],
            'errors' => fn () => ['outcomes.en' => __('validation.string', ['attribute' => __('Project outcome (English)')])],
        ],
        'Public outcome is missing' => [
            'state' => ['public_outcomes' => null],
            'errors' => fn () => ['public_outcomes' => __('You must indicate if the reports will be publicly available.')],
        ],
        'Public outcome not a boolean' => [
            'state' => ['public_outcomes' => 123],
            'errors' => fn () => ['public_outcomes' => __('validation.boolean', ['attribute' => __('public outcomes')])],
        ],
    ];
});
