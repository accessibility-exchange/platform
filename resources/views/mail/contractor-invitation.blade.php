@component('mail::message')
{{ __('You have been invited to join the :invitationable_type “:invitationable” as a :role!', ['invitationable_type' => $invitation->invitationable_type, 'invitationable' => $invitation->invitationable->name, 'role' => App\Enums\IndividualRole::labels()[$invitation->role]]) }}

{{ __('If you do not have an account, you may create one by clicking the button below. After creating an account, you may click the invitation acceptance button in this email to accept the invitation:') }}

@component('mail::button',
    [
        'url' => localized_route('register', [
            'invitation' => 1,
            'context' => 'individual',
            'email' => $invitation->email,
        ]),
    ])
{{ __('Create Account') }}
@endcomponent

{{ __('If you already have an account, you may accept this invitation by clicking the button below:') }}

@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

{{ __('If you did not expect to receive an invitation to this :invitationable_type, you may discard this email.', ['invitationable_type' => $invitation->invitationable_type]) }}
@endcomponent
