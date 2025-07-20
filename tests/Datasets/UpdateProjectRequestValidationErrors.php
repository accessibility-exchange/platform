<?php

use Carbon\Carbon;

dataset('updateProjectRequestValidationErrors', function () {
    return [
        'Name is missing' => fn () => [
            'state' => ['name' => null],
            'errors' => [
                'name.en' => __('A project name must be provided in at least one language.'),
                'name.fr' => __('A project name must be provided in at least one language.'),
            ],
        ],
        'Name is missing required translation' => fn () => [
            'state' => ['name' => ['es' => 'Nombre del proyecto']],
            'errors' => [
                'name.en' => __('A project name must be provided in at least one language.'),
                'name.fr' => __('A project name must be provided in at least one language.'),
            ],
            'without' => ['name'],
        ],
        'Name translation is not a string' => fn () => [
            'state' => ['name.en' => false],
            'errors' => ['name.en' => __('validation.string', ['attribute' => __('Project name (English)')])],
        ],
        'Goal is missing' => fn () => [
            'state' => ['goals' => null],
            'errors' => [
                'goals.en' => __('Project goals must be provided in either English or French.'),
                'goals.fr' => __('Project goals must be provided in either English or French.'),
            ],
        ],
        'Goal is missing required translation' => fn () => [
            'state' => ['goals' => ['es' => 'Objetivos del proyecto']],
            'errors' => [
                'goals.en' => __('Project goals must be provided in either English or French.'),
                'goals.fr' => __('Project goals must be provided in either English or French.'),
            ],
            'without' => ['goals'],
        ],
        'Goal translation is not a string' => fn () => [
            'state' => ['goals.en' => false],
            'errors' => ['goals.en' => __('validation.string', ['attribute' => __('Project goals (English)')])],
        ],
        'Scope is missing' => fn () => [
            'state' => ['scope' => null],
            'errors' => [
                'scope.en' => __('Project scope must be provided in either English or French.'),
                'scope.fr' => __('Project scope must be provided in either English or French.'),
            ],
        ],
        'Scope is missing required translation' => fn () => [
            'state' => ['scope' => ['es' => 'Objetivos del proyecto']],
            'errors' => [
                'scope.en' => __('Project scope must be provided in either English or French.'),
                'scope.fr' => __('Project scope must be provided in either English or French.'),
            ],
            'without' => ['scope'],
        ],
        'Scope translation is not a string' => fn () => [
            'state' => ['scope.en' => false],
            'errors' => ['scope.en' => __('validation.string', ['attribute' => __('Project scope (English)')])],
        ],
        'Region is missing' => fn () => [
            'state' => ['regions' => null],
            'errors' => ['regions' => __('validation.required', ['attribute' => __('geographic areas')])],
        ],
        'Region is not a valid province or territory' => fn () => [
            'state' => ['regions' => ['XX']],
            'errors' => ['regions.0' => __('validation.exists', ['attribute' => __('geographic areas')])],
        ],
        'Impacts is missing' => fn () => [
            'state' => ['impacts' => null],
            'errors' => ['impacts' => __('validation.required', ['attribute' => __('areas of impact')])],
        ],
        'Impacts not an array' => fn () => [
            'state' => ['impacts' => 1000000],
            'errors' => ['impacts' => __('validation.array', ['attribute' => __('areas of impact')])],
        ],
        'Impact does not exist' => fn () => [
            'state' => ['impacts' => [1000000]],
            'errors' => ['impacts.0' => __('validation.exists', ['attribute' => __('areas of impact')])],
        ],
        'Out of scope not an array' => fn () => [
            'state' => ['out_of_scope' => 'out of scope'],
            'errors' => ['out_of_scope' => __('validation.array', ['attribute' => __('out of scope')])],
        ],
        'Out of scope message not a string' => fn () => [
            'state' => ['out_of_scope.en' => false],
            'errors' => ['out_of_scope.en' => __('validation.string', ['attribute' => __('out of scope')])],
        ],
        'Start Date is missing' => fn () => [
            'state' => ['start_date' => null],
            'errors' => ['start_date' => __('validation.required', ['attribute' => __('start date')])],
        ],
        'Start Date after End Date' => fn () => [
            'state' => [
                'start_date' => Carbon::now()->addMonth(),
                'end_date' => Carbon::now()->subMonth(),
            ],
            'errors' => [
                'start_date' => __('validation.before', ['attribute' => __('start date'), 'date' => __('end date')]),
                'end_date' => __('validation.after', ['attribute' => __('end date'), 'date' => __('start date')]),
            ],
        ],
        'End Date is missing' => fn () => [
            'state' => ['end_date' => null],
            'errors' => ['end_date' => __('validation.required', ['attribute' => __('end date')])],
        ],

        'Outcome analysis is missing' => fn () => [
            'state' => ['outcome_analysis' => null],
            'errors' => [
                'outcome_analysis' => __('You must identify who will be going through the results and producing an outcome.'),
                'has_other_outcome_analysis' => __('You must identify who will be going through the results and producing an outcome.'),
            ],
        ],
        'Outcome analysis translation is invalid' => fn () => [
            'state' => ['outcome_analysis.en' => 'outsourced'],
            'errors' => ['outcome_analysis.en' => __('validation.exists', ['attribute' => __('Outcomes and reports')])],
        ],
        'Other outcome analysis translation is missing' => fn () => [
            'state' => ['has_other_outcome_analysis' => true],
            'errors' => [
                'outcome_analysis_other.en' => __('You must identify the other team that will be going through the results and producing an outcome.'),
                'outcome_analysis_other.fr' => __('You must identify the other team that will be going through the results and producing an outcome.'),
            ],
            'without' => ['outcome_analysis_other'],
        ],
        'Other outcome analysis translation is not a string' => fn () => [
            'state' => ['outcome_analysis_other.en' => 123, 'has_other_outcome_analysis' => true],
            'errors' => ['outcome_analysis_other.en' => __('validation.string', ['attribute' => __('Outcomes and reports other (English)')])],
        ],

        'Outcome is missing' => fn () => [
            'state' => ['outcomes' => null],
            'errors' => [
                'outcomes.en' => __('Tangible outcomes must be provided in either English or French.'),
                'outcomes.fr' => __('Tangible outcomes must be provided in either English or French.'),
            ],
        ],
        'Outcome is missing required translation' => fn () => [
            'state' => ['outcomes' => ['es' => 'Resultados del proyecto']],
            'errors' => [
                'outcomes.en' => __('Tangible outcomes must be provided in either English or French.'),
                'outcomes.fr' => __('Tangible outcomes must be provided in either English or French.'),
            ],
            'without' => ['outcomes'],
        ],
        'Outcome translation is not a string' => fn () => [
            'state' => ['outcomes.en' => false],
            'errors' => ['outcomes.en' => __('validation.string', ['attribute' => __('Project outcome (English)')])],
        ],
        'Public outcome is missing' => fn () => [
            'state' => ['public_outcomes' => null],
            'errors' => ['public_outcomes' => __('You must indicate if the reports will be publicly available.')],
        ],
        'Public outcome not a boolean' => fn () => [
            'state' => ['public_outcomes' => 123],
            'errors' => ['public_outcomes' => __('validation.boolean', ['attribute' => __('public outcomes')])],
        ],
    ];
});
