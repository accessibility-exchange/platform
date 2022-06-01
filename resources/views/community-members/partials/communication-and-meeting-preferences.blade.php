<h3>{{ __('Preferred contact method') }}</h3>

@markdown
{{ $communityMember->primary_contact_method }}
@endmarkdown

@if($communityMember->alternate_contact_point)
<h3>{{ __('Alternate contact method') }}</h3>

@switch($communityMember->preferred_contact_method)
    @case('phone')
        <h4>{{ __('Email') }}</h4>
        @break
    @case('email')
        <h4>{{ __('Phone') }}</h4>
        @break
    @default
        <h4>{{ __('Phone') }}</h4>
@endswitch
    @markdown
    {{ $communityMember->alternate_contact_method }}
    @endmarkdown
@endif

<h3>{{ __('Types of meetings offered') }}</h3>

<ul>
@foreach($communityMember->meeting_types as $meeting_type)
    <li>{{ $communityMember->getMeetingType($meeting_type) }}</li>
@endforeach
</ul>
