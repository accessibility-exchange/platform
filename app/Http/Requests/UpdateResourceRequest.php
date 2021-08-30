<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $resource = $this->route('resource');

        return $resource && $this->user()->can('update', $resource);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $resource = $this->route('resource');

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Resource::class)->ignore($resource->id),

            ],
            'language' => [
                'required',
                Rule::in(config('locales.supported')),
            ],
            'summary' => 'required|string',
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
            'title.unique' => __('validation.custom.resource.title_exists'),
        ];
    }
}
