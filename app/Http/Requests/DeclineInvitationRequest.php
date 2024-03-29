<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeclineInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->email === $this->invitation->email;
    }

    public function rules(): array
    {
        return [];
    }
}
