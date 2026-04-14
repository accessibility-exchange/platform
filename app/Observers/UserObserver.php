<?php

namespace App\Observers;

use App\Enums\IndividualRole;
use App\Enums\UserContext;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\IndividualContractorInvited;
use App\Notifications\NewUserRegistered;
use App\Notifications\ParticipantInvited;
use App\Notifications\UserAccountDeleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use ParagonIE\CipherSweet\CipherSweet as CipherSweetEngine;
use ParagonIE\CipherSweet\EncryptedField;

class UserObserver
{
    public function created(User $user): void
    {
        $email = (new EncryptedField(
            app(CipherSweetEngine::class),
            'users',
            'email'
        ))->decryptValue($user->email);

        foreach (
            Invitation::where([
                ['email', $email],
                ['role', IndividualRole::ConsultationParticipant->value],
            ])->get() as $invitation
        ) {
            $user->notify(new ParticipantInvited($invitation, true));
        }

        foreach (
            Invitation::where([
                ['email', $email],
                ['role', IndividualRole::CommunityConnector->value],
            ])->get() as $invitation
        ) {
            $user->notify(new IndividualContractorInvited($invitation, true));
        }

        if (! in_array($user->context, [UserContext::Administrator->value,
            UserContext::Organization->value,
            UserContext::RegulatedOrganization->value,
        ])) {

            $admins = User::whereAdministrator()->get();
            Notification::send($admins, new NewUserRegistered(
                user: $user,
            ));
        }
    }

    public function deleting(User $user): void
    {
        $organization = match ($user->context) {
            UserContext::Organization->value => $user->organization,
            UserContext::RegulatedOrganization->value => $user->regulatedOrganization,
            default => null,
        };

        $admins = User::whereAdministrator()->where('id', '!=', $user->id)->get();

        Notification::send($admins, new UserAccountDeleted(
            userName: $user->name,
            userContext: UserContext::from($user->context),
            account: $organization,
        ));

        if (in_array($user->context, [UserContext::Individual->value, UserContext::TrainingParticipant->value], true)) {
            $this->clearNewUserRegisteredNotifications($user);
        }
    }

    /**
     * Clear NewUserRegistered notifications that reference this user.
     *
     * These notifications store the user's ID in their data payload and resolve the user
     * at render time.
     */
    protected function clearNewUserRegisteredNotifications(User $user): void
    {
        DB::table('notifications')
            ->where('type', NewUserRegistered::class)
            ->where('data->user_id', $user->id)
            ->delete();
    }
}
