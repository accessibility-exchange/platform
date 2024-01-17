<?php

namespace App\Http\Requests;

use App\Enums\ProvinceOrTerritory;
use App\Models\RegulatedOrganization;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateProjectRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->project);
    }

    public function rules(): array
    {
        return [
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'name.*' => [
                'nullable',
                'string',
                'max:255',
                UniqueTranslationRule::for('projects')->ignore($this->project->id),
            ],
            'goals.en' => 'required_without:goals.fr',
            'goals.fr' => 'required_without:goals.en',
            'goals.*' => 'nullable|string',
            'scope.en' => 'required_without:scope.fr',
            'scope.fr' => 'required_without:scope.en',
            'scope.*' => 'nullable|string',
            'regions' => 'required|array',
            'regions.*' => [
                'nullable',
                new Enum(ProvinceOrTerritory::class),
            ],
            'impacts' => 'array',
            'impacts.*' => 'nullable|exists:impacts,id',
            'out_of_scope' => 'nullable|array',
            'out_of_scope.*' => 'nullable|string',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'outcome_analysis' => 'required_without:has_other_outcome_analysis|array',
            'outcome_analysis.*' => 'string|in:internal,external',
            'has_other_outcome_analysis' => 'required_without:outcome_analysis|boolean',
            'outcome_analysis_other' => 'nullable|array|exclude_if:has_other_outcome_analysis,false|exclude_unless:has_other_outcome_analysis,true',
            'outcome_analysis_other.*' => 'nullable|string',
            'outcomes.en' => 'required_without:outcomes.fr',
            'outcomes.fr' => 'required_without:outcomes.en',
            'outcomes.*' => 'nullable|string',
            'public_outcomes' => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name.en' => __('Project name (English)'),
            'name.fr' => __('Project name (French)'),
            'name.*' => __('Project name'),
            'goals.en' => __('Project goals (English)'),
            'goals.fr' => __('Project goals (French)'),
            'goals.*' => __('Project goals'),
            'scope.en' => __('Project scope (English)'),
            'scope.fr' => __('Project scope (French)'),
            'scope' => __('Project scope'),
            'regions' => __('geographic areas'),
            'regions.*' => __('geographic areas'),
            'impacts' => __('areas of impact'),
            'impacts.*' => __('areas of impact'),
            'out_of_scope' => __('out of scope'),
            'out_of_scope.*' => __('out of scope'),
            'start_date' => __('start date'),
            'end_date' => __('end date'),
            'outcome_analysis' => __('Outcomes and reports'),
            'outcome_analysis.*' => __('Outcomes and reports'),
            'has_other_outcome_analysis' => __('Outcomes and reports other'),
            'outcome_analysis_other' => __('Outcomes and reports other'),
            'outcome_analysis_other.en' => __('Outcomes and reports other (English)'),
            'outcome_analysis_other.fr' => __('Outcomes and reports other (French)'),
            'outcome_analysis_other.*' => __('Outcomes and reports other'),
            'outcomes.en' => __('Project outcome (English)'),
            'outcomes.fr' => __('Project outcome (French)'),
            'outcomes.*' => __('Project outcome'),
            'public_outcomes' => __('public outcomes'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.*.unique_translation' => __('A project with this name already exists.'),
            'name.*.required_without' => __('A project name must be provided in at least one language.'),
            'goals.*.required_without' => __('Project goals must be provided in at least one language.'),
            'scope.*.required_without' => __('Project scope must be provided in at least one language.'),
            'outcome_analysis.required_without' => __('You must identify who will be going through the results and producing an outcome.'),
            'has_other_outcome_analysis.required_without' => __('You must identify who will be going through the results and producing an outcome.'),
            'outcome_analysis_other.*.required_without' => __('You must identify the other team that will be going through the results and producing an outcome.'),
            'outcomes.*.required_without' => __('Tangible outcomes must be provided in at least one language.'),
            'public_outcomes.required' => __('You must indicate if the reports will be publicly available.'),
        ];
    }

    public function prepareForValidation()
    {
        $fallbacks = [
            'impacts' => [],
            'regions' => [],
            'outcome_analysis' => [],
        ];

        // Prepare input for validation
        $this->mergeIfMissing($fallbacks);

        // Prepare old input in case of validation failure
        request()->mergeIfMissing($fallbacks);

        // Prevents the model values from coming back if the other outcome analysis options have been removed when a
        // validation error occurs.
        request()->mergeIfMissing([
            'outcome_analysis_other' => [],
            'has_other_outcome_analysis' => 0,
        ]);
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('impacts', 'required', function ($input) {
            return $this->project->projectable instanceof RegulatedOrganization;
        });

        $validator->sometimes('outcome_analysis_other.en', 'required_without:outcome_analysis_other.fr', function ($input) {
            return ! empty($input->has_other_outcome_analysis);
        });

        $validator->sometimes('outcome_analysis_other.fr', 'required_without:outcome_analysis_other.en', function ($input) {
            return ! empty($input->has_other_outcome_analysis);
        });
    }
}
