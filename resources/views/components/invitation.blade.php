<div class="notification stack">
    <span class="notification-dot absolute left-10 top-14"></span>
    <p class="h4">{{ __('invitation.invitation_title') }}</p>
    <x-interpretation name="{{ __('invitation.invitation_title', [], 'en') }}" namespace="invitation" />
    {{ $slot }}
</div>
