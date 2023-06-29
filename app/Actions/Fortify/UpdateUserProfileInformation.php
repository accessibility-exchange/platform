<?php

namespace App\Actions\Fortify;

use App\Rules\UniqueUserEmail;
use App\Traits\UserEmailVerification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    use UserEmailVerification;

    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     */
    public function update($user, array $input): void
    {
        Validator::make($input, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                new UniqueUserEmail($user->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        if (
            Str::lower($input['email']) !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input['email']);
        } else {
            $user->forceFill([
                'email' => $input['email'],
            ])->save();
        }

        flash(__('Your information has been updated.'), 'success');
    }
}
