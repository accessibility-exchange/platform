@component('mail::message')
{{ __('You have been invited to join the :invitationable_type “:invitationable” as a :role.', ['invitationable_type' => $invitation->invitationable->singular_name, 'invitationable' => $invitation->invitationable->name, 'role' => App\Enums\IndividualRole::labels()[$invitation->role]]) }}

{{ __('Please:') }}

{{ __('1. Create an account, if you don’t already have one.') }}
@component('mail::button',
[
    'url' => localized_route('register', [
        'invitation' => 1,
        'context' => 'individual',
        'role' => $invitation->role,
        'email' => $invitation->email,
    ]),
])
{{ __('Create Account') }}
@endcomponent

{{ __('2. Accept your invitation by clicking the button below.') }}

@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

{{ __('If you did not expect to receive an invitation to this :invitationable_type, you may discard this email.', ['invitationable_type' => $invitation->invitationable->singular_name]) }}
@endcomponent
