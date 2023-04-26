<?php

namespace App\Http\Requests;

use App\Enums\TeamRole;
use App\Rules\NotLastAdmin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateMembershipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->membership->membershipable());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'string',
                new Enum(TeamRole::class),
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator
            ->sometimes(
                'role',
                [new NotLastAdmin($this->membership)],
                function ($input) {
                    return $this->membership->role === 'admin' && $input->role !== 'admin';
                }
            );
    }
}
