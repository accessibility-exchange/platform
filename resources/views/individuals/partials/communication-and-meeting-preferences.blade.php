<h3>{{ __('Preferred contact method') }}</h3>

@markdown
{{ $individual->primary_contact_method }}
@endmarkdown

@if($individual->alternate_contact_point)
<h3>{{ __('Alternate contact method') }}</h3>

@switch($individual->preferred_contact_method)
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
    {{ $individual->alternate_contact_method }}
    @endmarkdown
@endif

<h3>{{ __('Types of meetings offered') }}</h3>

<ul>
@foreach($individual->meeting_types as $meeting_type)
    <li>{{ App\Enums\MeetingTypes::from($meeting_type)->labels()[$meeting_type] }}</li>
@endforeach
</ul>
