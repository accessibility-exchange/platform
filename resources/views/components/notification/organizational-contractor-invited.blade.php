<x-notification :notification="$notification">
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="body">{{ $body }}</x-slot>
    <x-slot name="actions">
        <a class="cta secondary"
            href="{{ localized_route($invitationable->getRoutePrefix() . '.show', $invitationable) }}">{{ __('Learn more') }}</a>
        <a class="cta secondary"
            href="{{ URL::signedRoute('contractor-invitations.accept', $invitation) }}">{{ __('Accept') }}</a>
        <form class="inline" action="{{ route('invitations.decline', $invitation) }}" method="post">
            @csrf
            @method('delete')
            <button class="secondary">{{ __('Decline') }}</button>
        </form>
    </x-slot>
</x-notification>
