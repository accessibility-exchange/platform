<x-privacy-indicator level="private">
    <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to federally regulated organizations who work with you.') }}
</x-privacy-indicator>
<p><a class="button" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 5]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Access needs') . '</span>']) !!}</a></p>
