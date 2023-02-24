<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->course->quiz->questions as $question) {
            $rules['questions.'.$question->id] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'questions.*' => __('You must answer this question'),
        ];
    }
}
