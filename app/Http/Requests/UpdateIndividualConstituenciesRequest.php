<?php

namespace App\Http\Requests;

use App\Models\AgeBracket;
use App\Models\Constituency;
use App\Models\LivedExperience;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIndividualConstituenciesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lived_experience_connections' => [
                'nullable',
                Rule::requiredIf($this->individual->isConnector()),
                'array',
                Rule::in(array_merge(LivedExperience::pluck('id')->toArray(), ['other'])),
            ],
            'other_lived_experience_connections' => 'nullable|array:'.implode(',', $this->individual->languages),
            'constituency_connections' => [
                'nullable',
                'array',
                Rule::in(array_merge(Constituency::pluck('id')->toArray(), ['other'])),
            ],
            'other_constituency_connections' => 'nullable|array:'.implode(',', $this->individual->languages),
            'age_bracket_connections' => [
                'nullable',
                'array',
                Rule::in(AgeBracket::pluck('id')->toArray()),
            ],

        ];
    }
}
