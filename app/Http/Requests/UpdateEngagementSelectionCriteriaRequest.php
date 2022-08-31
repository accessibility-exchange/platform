<?php

namespace App\Http\Requests;

use App\Enums\ProvinceOrTerritory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Worksome\RequestFactories\Concerns\HasFactory;

class UpdateEngagementSelectionCriteriaRequest extends FormRequest
{
    use HasFactory;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'regions' => 'nullable|array',
            'regions.*' => [new Enum(ProvinceOrTerritory::class)],
            'locations' => 'nullable|array',
            'locations.*.region' => ['required', new Enum(ProvinceOrTerritory::class)],
            'locations.*.locality' => 'required|string',
            'ideal_participants' => 'required|integer',
            'minimum_participants' => 'required|integer|lte:ideal_participants',
        ];
    }

    public function messages(): array
    {
        return [
            'minimum_participants.lte' => __('The minimum number of participants is more than the ideal number of participants. Please enter a minimum that is less than or the same as the ideal number of participants.'),
        ];
    }
}
