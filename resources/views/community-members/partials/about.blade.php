@can('update', $communityMember)
<x-privacy-indicator level="public">
    <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
</x-privacy-indicator>
<p><a class="button" href="{{ localized_route('community-members.edit', $communityMember) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a></p>
@endcan

@if($communityMember->bio)
<x-markdown class="stack">{{ $communityMember->bio }}</x-markdown>
@endif

@if($communityMember->links)
<h4>{{ $communityMember->firstName() }}’s links</h4>
<ul>
    @foreach($communityMember->links as $key => $link)
    <li><a href="{{ $link }}" rel="external">{{ $key }}</a></li>
    @endforeach
</ul>
@endif
