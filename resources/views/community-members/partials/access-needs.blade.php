<x-privacy-indicator level="private">
    <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
</x-privacy-indicator>
<p><a class="button" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Access needs') . '</span>']) !!}</a></p>
