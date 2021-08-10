<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $organization = $this->route('organization');

        return $organization && $this->user()->can('update', $organization);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $organization = $this->route('organization');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Organization::class)->ignore($organization->id),

            ],
            'locality' => ['required', 'string', 'max:255'],
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
        ];
    }
}
