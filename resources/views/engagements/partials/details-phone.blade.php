@can('participate', $engagement)
    <p><strong>{{ __('Phone number:') }}</strong> {{ $meeting->meeting_phone->formatForCountry('CA') }}</p>
    @if ($meeting->additional_phone_information)
        {{ safe_nl2br($meeting->additional_phone_information) }}
    @endif
@endcan
