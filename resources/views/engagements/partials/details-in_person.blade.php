@can('participate', $engagement)
    <p><span class="font-semibold">{{ __('Address') }}:</span></p>
    <address class="mt-0">
        {{ $meeting->street_address }}<br />
        @if ($meeting->unit_suite_room)
            {{ $meeting->unit_suite_room }}<br />
        @endif
        {{ $meeting->locality }}, {{ \App\Enums\ProvinceOrTerritory::labels()[$meeting->region] }}
        {{ $meeting->postal_code }}
    </address>

    @if ($meeting->directions)
        <div><span class="font-semibold">{{ __('Further directions') }}:</span>
            {{ $meeting->directions }}
        </div>
    @endif
@else
    <p><strong>{{ __('Address') }}:</strong></p>
    <address class="mt-0">
        {{ $meeting->locality }}, {{ \App\Enums\ProvinceOrTerritory::labels()[$meeting->region] }}
    </address>
@endcan
