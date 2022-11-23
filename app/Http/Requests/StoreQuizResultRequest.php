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
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $questionRules = [];
        $quiz = $this->route('quiz');
        foreach ($quiz->questions as $question) {
            $questionRules['question_'.$question->id] = 'required|array';
        }

        return $questionRules;
    }
}
