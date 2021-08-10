<?php

namespace App\Http\Requests;

use App\Models\Entity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEntityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $entity = $this->route('entity');

        return $entity && $this->user()->can('update', $entity);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $entity = $this->route('entity');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Entity::class)->ignore($entity->id),

            ],
            'locality' => ['required', 'string', 'max:255'],
            'region' => [
                'required',
                Rule::in(get_region_codes()),
            ],
        ];
    }
}
