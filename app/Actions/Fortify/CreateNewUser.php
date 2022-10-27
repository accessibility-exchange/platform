<?php

namespace App\Actions\Fortify;

use App\Enums\UserContext;
use App\Models\User;
use App\Rules\UniqueUserEmail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return User
     */
    public function create(array $input): User
    {
        $input['locale'] = session('locale');
        $input['signed_language'] = session('signed_language');
        $input['name'] = session('name');
        $input['email'] = session('email');
        $input['context'] = session('context');
        $input['accepted_privacy_policy'] = array_key_exists('accepted_privacy_policy', $input);
        $input['accepted_terms_of_service'] = array_key_exists('accepted_terms_of_service', $input);

        if (session('roles') || session('invitation')) {
            $input['extra_attributes'] = [];

            if (session('invited_role')) {
                $input['extra_attributes']['invited_role'] = session('invited_role');
            }

            if (session('invitation')) {
                $input['extra_attributes']['invitation'] = 1;
            }
        }

        Validator::make(
            $input,
            [
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    new UniqueUserEmail(),
                ],
                'password' => $this->passwordRules(),
                'context' => [
                    'required',
                    'string',
                    new Enum(UserContext::class),
                    Rule::notIn([UserContext::Administrator->value]),
                ],
                'extra_attributes' => 'nullable|array',
                'locale' => ['required', Rule::in(config('locales.supported', ['en', 'fr']))],
                'signed_language' => 'nullable|string|in:ase,fcs',
                'accepted_privacy_policy' => 'accepted',
                'accepted_terms_of_service' => 'accepted',
            ],
            [
                'accepted_privacy_policy.accepted' => __('You must agree to the privacy policy.'),
                'accepted_terms_of_service.accepted' => __('You must agree to the terms of service.'),
            ]
        )->validate();

        Cookie::queue('theme', 'light');
        Cookie::queue('locale', $input['locale']);

        session()->forget('locale');
        session()->forget('signed_language');
        session()->forget('context');
        session()->forget('name');
        session()->forget('email');

        return User::create([
            'name' => $input['name'],
            'email' => Str::lower($input['email']),
            'password' => Hash::make($input['password']),
            'context' => $input['context'],
            'locale' => $input['locale'],
            'signed_language' => $input['signed_language'],
            'extra_attributes' => $input['extra_attributes'] ?? null,
            'accepted_privacy_policy_at' => now(),
            'accepted_terms_of_service_at' => now(),
        ]);
    }
}
