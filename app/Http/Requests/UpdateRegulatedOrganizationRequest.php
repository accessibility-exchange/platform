<?php

namespace App\Http\Requests;

use App\Models\RegulatedOrganization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegulatedOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->regulatedOrganization);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(RegulatedOrganization::class)->ignore($this->regulatedOrganization->id),

            ],
            'locality' => ['required', 'string', 'max:255'],
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
        ];
    }
}
