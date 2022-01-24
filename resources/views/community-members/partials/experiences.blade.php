<x-privacy-indicator level="private">
    <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
</x-privacy-indicator>
<p><a class="button" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Experiences') . '</span>']) !!}</a></p>
@if(!$communityMember->livedExperiences->isEmpty())
<x-heading :level="$level">{{ __('Lived experience') }}</x-heading>
<ul role="list" class="tags">
    @foreach($communityMember->livedExperiences as $livedExperience)
    <li>{{ $livedExperience->name }}</li>
    @endforeach
</ul>
@endif
