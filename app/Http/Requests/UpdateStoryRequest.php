<?php

namespace App\Http\Requests;

use App\Models\Story;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $story = $this->route('story');

        return $story && $this->user()->can('update', $story);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $story = $this->route('story');

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Story::class)->ignore($story->id),

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
            'title.unique' => __('validation.custom.story.title_exists'),
        ];
    }
}
