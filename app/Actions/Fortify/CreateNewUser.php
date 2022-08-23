<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Rules\UniqueUserEmail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
                    Rule::in(config('app.contexts')),
                ],
                'extra_attributes' => 'nullable|array',
                'locale' => ['required', Rule::in(config('locales.supported', ['en', 'fr']))],
                'signed_language' => 'nullable|string|in:ase,fcs',
            ],
            [

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
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'context' => $input['context'],
            'locale' => $input['locale'],
            'signed_language' => $input['signed_language'],
            'extra_attributes' => $input['extra_attributes'] ?? null,
        ]);
    }
}
