<h3>{{ __('Preferred contact method') }}</h3>

{!! Str::markdown($individual->user->primary_contact_method) !!}

@if ($individual->user->alternate_contact_point)
    <h3>{{ __('Alternate contact method') }}</h3>

    @switch($individual->user->preferred_contact_method)
        @case('phone')
            <h4>{{ __('Email') }}</h4>
        @break

        @case('email')
            <h4>{{ __('Phone') }}</h4>
        @break

        @default
            <h4>{{ __('Phone') }}</h4>
    @endswitch
    {!! Str::markdown($individual->user->alternate_contact_method) !!}
@endif

<h3>{{ __('Types of meetings offered') }}</h3>

<ul>
    @foreach ($individual->meeting_types ?? [] as $meeting_type)
        <li>{{ App\Enums\MeetingType::from($meeting_type)->labels()[$meeting_type] }}</li>
    @endforeach
</ul>
