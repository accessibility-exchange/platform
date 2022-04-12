<?php

namespace App\Actions\Fortify;

use App\Models\User;
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
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $input['locale'] = session('locale');
        $input['signed_language'] = session('signed_language');
        $input['name'] = session('name');
        $input['email'] = session('email');
        $input['context'] = session('context');

        Validator::make(
            $input,
            [
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique(User::class),
                ],
                'password' => $this->passwordRules(),
                'context' => [
                    'required',
                    'string',
                    Rule::in(config('app.contexts')),
                ],
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
        ]);
    }
}
