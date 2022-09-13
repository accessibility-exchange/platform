@component('mail::message')
{{ __('Your organization has been invited to join the :invitationable_type “:invitationable” as a :role!', ['invitationable_type' => $invitation->invitationable_type, 'invitationable' => $invitation->invitationable->name, 'role' => App\Enums\OrganizationRole::labels()[$invitation->role]]) }}

{{ __('You may accept this invitation by clicking the button below:') }}

@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

{{ __('If you did not expect to receive an invitation to this :invitationable_type, you may discard this email.', ['invitationable_type' => $invitation->invitationable_type]) }}
@endcomponent
