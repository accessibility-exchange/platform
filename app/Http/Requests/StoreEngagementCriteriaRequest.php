<?php

namespace App\Http\Requests;

use App\Enums\ProvinceOrTerritory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEngagementCriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location_type' => 'required|in:regions,localities',
            'regions' => 'nullable|array|exclude_if:location_type,localities',
            'regions.*' => [new Enum(ProvinceOrTerritory::class)],
            'locations' => 'nullable|array|exclude_if:location_type,regions',
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

    public function prepareForValidation()
    {
        $this->mergeIfMissing([
            'regions' => [],
            'locations' => [],
        ]);
    }
}
