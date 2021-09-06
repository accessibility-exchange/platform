<?php

namespace App\Http\Requests;

use App\Models\Consultant;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsultantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $consultant = $this->route('consultant');

        return $consultant && $this->user()->can('update', $consultant);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $consultant = $this->route('consultant');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Consultant::class)->ignore($consultant->id),

            ],
            'picture' => 'nullable|file|image|dimensions:min_width=200,min_height=200',
            'bio' => 'required|string',
            'locality' => 'required|string|max:255',
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
            'birth_date' => 'nullable|date',
            'pronouns' => 'nullable|string',
            'creator' => 'required|in:self,other',
            'creator_name' => 'required_if:creator,other|nullable|string|max:255',
            'creator_relationship' => 'required_if:creator,other|nullable|string|max:255',
            'visibility' => 'required|in:project,all',
            'user_id' => [
                Rule::unique(Consultant::class),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'A consultant page with this name already exists.',
        ];
    }
}
