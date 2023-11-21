<x-app-layout page-width="medium">
    <x-slot name="title">{{ $meeting->id ? __('Edit meeting') : __('Add new meeting') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.manage', $project) }}">{{ $project->name }}</a></li>
            <li><a href="{{ localized_route('engagements.manage', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        @if ($meeting->id)
            <h1>{{ __('Edit meeting') }}</h1>
            <x-interpretation name="{{ __('Edit meeting', [], 'en') }}" />
        @else
            <h1>{{ __('Add new meeting') }}</h1>
            <x-interpretation name="{{ __('Add new meeting', [], 'en') }}" />
        @endif
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack"
        action="{{ $meeting->id ? localized_route('meetings.update', ['meeting' => $meeting, 'engagement' => $engagement]) : localized_route('meetings.store', $engagement) }}"
        method="post" novalidate>
        @csrf
        @if ($meeting->id)
            @method('put')
        @endif

        <x-translatable-input name="title" :label="__('Title of meeting') . ' ' . __('(required)')" :short-label="__('meeting title')" :model="$meeting" />

        <hr class="divider--thick" />

        <h2>{{ __('Time and date') }}</h2>
        <x-interpretation name="{{ __('Time and date', [], 'en') }}" />

        <div class="field">
            <x-date-picker name="date" :label="__('Date') . ' ' . __('(required)')" :value="old('date', $meeting->date?->format('Y-m-d') ?? '')" />
        </div>
        <fieldset class="mt-12">
            <legend>{{ __('Time') . ' ' . __('(required)') }}</legend>
            <x-interpretation name="{{ __('Time', [], 'en') }}" />
            <x-hearth-hint for="time_format">
                {{ __('Please use the 24 hour clock time. For example, 13:00 is 1pm.') }}
            </x-hearth-hint>
            <div class="flex gap-6">
                <div class="field @error('start_time') field--error @enderror">
                    <x-hearth-label for="start_time">{{ __('Start time') }}</x-hearth-label>
                    <x-hearth-input class="w-full" name="start_time" :value="old('start_time', $meeting->start_time?->format('G:i'))" hinted="time_format-hint" />
                    <x-hearth-error for="start_time" />
                </div>
                <div class="field @error('end_time') field--error @enderror">
                    <x-hearth-label for="end_time">{{ __('End time') }}</x-hearth-label>
                    <x-hearth-input class="w-full" name="end_time" :value="old('end_time', $meeting->end_time?->format('G:i'))" hinted="time_format-hint" />
                    <x-hearth-error for="end_time" />
                </div>
            </div>
            <div class="field @error('timezone') field--error @enderror mt-6">
                <x-hearth-label for="timezone">{{ __('Time zone') . ' ' . __('(required)') }}</x-hearth-label>
                <x-interpretation name="{{ __('Time zone', [], 'en') }}" />
                <x-hearth-select class="w-1/2" name="timezone" :options="$timezones" :selected="old('timezone', $meeting->timezone)" hinted />
                <div class="field__hint" id="timezone-hint">
                    <p>{{ __('*Yukon and parts of British Columbia observe Mountain Standard Time all year.') }}
                    </p>
                    <p>{{ __('**Saskatchewan observes Central Standard Time all year.') }}</p>
                </div>
                <x-hearth-error for="timezone" />
            </div>
        </fieldset>

        <hr class="divider--thick" />

        <h2>{{ __('Ways to attend') . ' ' . __('(required)') }}</h2>
        <x-interpretation name="{{ __('Ways to attend', [], 'en') }}" />
        <div x-data="{ meetingTypes: {{ json_encode(old('meeting_types', $meeting->meeting_types ?? [])) }} }">
            <div class="field @error('meeting_types') field--error @enderror">
                <x-hearth-checkbox id="meeting_types-in_person" name="meeting_types[]" value="in_person"
                    :checked="in_array('in_person', old('meeting_types', $meeting->meeting_types ?? []))" x-model="meetingTypes" />
                <x-hearth-label for="meeting_types-in_person">{{ __('In person') }}</x-hearth-label>
                <div class="box stack my-6" x-show="meetingTypes.includes('in_person')">
                    <div class="field @error('street_address') field--error @enderror">
                        <x-hearth-label for="street_address">{{ __('Street address') . ' ' . __('(required)') }}
                        </x-hearth-label>
                        <x-interpretation name="{{ __('Street address', [], 'en') }}" />
                        <x-hearth-input class="w-full" name="street_address" :value="old('street_address', $meeting->street_address)" required />
                        <x-hearth-error for="street_address" />
                    </div>
                    <div class="field @error('unit_suite_floor') field--error @enderror">
                        <x-hearth-label for="unit_suite_floor">{{ __('Unit, suite, or floor') }}</x-hearth-label>
                        <x-interpretation name="{{ __('Unit, suite, or floor', [], 'en') }}" />
                        <x-hearth-input class="w-full" name="unit_suite_floor" :value="old('unit_suite_floor', $meeting->unit_suite_floor)" required />
                        <x-hearth-error for="unit_suite_floor" />
                    </div>
                    <div class="field @error('locality') field--error @enderror">
                        <x-hearth-label for="locality">{{ __('City or town') . ' ' . __('(required)') }}
                        </x-hearth-label>
                        <x-interpretation name="{{ __('City or town', [], 'en') }}" />
                        <x-hearth-input class="w-full" name="locality" :value="old('locality', $meeting->locality)" required />
                        <x-hearth-error for="locality" />
                    </div>
                    <div class="field @error('region') field--error @enderror">
                        <x-hearth-label for="region">{{ __('Province or territory') . ' ' . __('(required)') }}
                        </x-hearth-label>
                        <x-interpretation name="{{ __('Province or territory', [], 'en') }}" />
                        <x-hearth-select name="region" :options="$regions" :selected="old('region', $meeting->region)" required />
                        <x-hearth-error for="region" />
                    </div>
                    <div class="field @error('postal_code') field--error @enderror">
                        <x-hearth-label for="postal_code">{{ __('Postal code') . ' ' . __('(required)') }}
                        </x-hearth-label>
                        <x-interpretation name="{{ __('Postal code', [], 'en') }}" />
                        <x-hearth-input class="w-full" name="postal_code" :value="old('postal_code', $meeting->postal_code)" required />
                        <x-hearth-error for="postal_code" />
                    </div>

                    <x-translatable-textarea name="directions" :label="__('Further directions')" :short-label="__('further directions')" :hint="__(
                        'Please be specific about where you would like the participants to go to participate in this engagement.',
                    )"
                        :model="$meeting" interpretationName="further directions" />
                </div>
            </div>
            <div class="field @error('meeting_types') field--error @enderror">
                <x-hearth-checkbox id="meeting_types-web_conference" name="meeting_types[]" value="web_conference"
                    :checked="in_array('web_conference', old('meeting_types', $meeting->meeting_types ?? []))" x-model="meetingTypes" />
                <x-hearth-label for="meeting_types-web_conference">{{ __('Virtual — video call') }}
                </x-hearth-label>
                <div class="box stack my-6" x-show="meetingTypes.includes('web_conference')">
                    <div class="field @error('meeting_software') field--error @enderror">
                        <x-hearth-label for="meeting_software">{{ __('Software') . ' ' . __('(required)') }}
                        </x-hearth-label>
                        <x-interpretation name="{{ __('Software', [], 'en') }}" />
                        <x-hearth-hint for="meeting_software">
                            {{ __('E.g. Microsoft Teams, Webex, Zoom.') }}
                        </x-hearth-hint>
                        <x-hearth-input name="meeting_software" :value="old('meeting_software', $meeting->meeting_software)" hinted />
                        <x-hearth-error for="meeting_software" />
                    </div>
                    <div class="field @error('alternative_meeting_software') field--error @enderror">
                        <x-hearth-checkbox name="alternative_meeting_software" :checked="old('alternative_meeting_software', $meeting->alternative_meeting_software) == 1" />
                        <x-hearth-label for="alternative_meeting_software">
                            {{ __('I can use other software if it is more accessible to participants') }}
                        </x-hearth-label>
                        <x-interpretation
                            name="{{ __('I can use other software if it is more accessible to participants', [], 'en') }}" />
                    </div>
                    <div class="field @error('meeting_url') field-error @enderror">
                        <x-hearth-label for="meeting_url" :value="__('Link to join') . ' ' . __('(required)')" />
                        <x-interpretation name="{{ __('Link to join', [], 'en') }}" />
                        <x-hearth-hint for="meeting_url">
                            {{ __('This will only be shared with participants who have accepted the invitation.') }}
                        </x-hearth-hint>
                        <x-hearth-input name="meeting_url" type="url" :value="old('meeting_url', $meeting->meeting_url)" hinted />
                        <x-hearth-error for="meeting_url" />
                    </div>
                    <x-translatable-textarea name="additional_video_information" :label="__('Additional information to join')" :short-label="__('additional information to join')"
                        :hint="__(
                            'For example, Meeting password, meeting ID. This will be shared only with participants who have accepted the invitation.',
                        )" :model="$meeting" interpretationName="additional information to join" />
                </div>
            </div>
            <div class="field @error('meeting_types') field--error @enderror">
                <x-hearth-checkbox id="meeting_types-phone" name="meeting_types[]" value="phone" :checked="in_array('phone', old('meeting_types', $meeting->meeting_types ?? []))"
                    x-model="meetingTypes" />
                <x-hearth-label for="meeting_types-phone">{{ __('Virtual — phone call') }}</x-hearth-label>
                <div class="box stack my-6" x-show="meetingTypes.includes('phone')">
                    <div class="field @error('meeting_phone') field-error @enderror">
                        <x-hearth-label for="meeting_phone" :value="__('Phone number to join') . ' ' . __('(required)')" />
                        <x-interpretation name="{{ __('Phone number to join', [], 'en') }}" />
                        <x-hearth-hint for="meeting_phone">
                            {{ __('This will only be shared with participants who have accepted the invitation.') }}
                        </x-hearth-hint>
                        <x-hearth-input name="meeting_phone" type="tel" :value="old('meeting_phone', $meeting->meeting_phone)" hinted />
                        <x-hearth-error for="meeting_phone" />
                    </div>
                    <x-translatable-textarea name="additional_phone_information" :label="__('Additional information to join')" :short-label="__('additional information to join')"
                        :hint="__(
                            'For example, Meeting password, meeting ID. This will be shared only with participants who have accepted the invitation.',
                        )" :model="$meeting" interpretationName="additional information to join" />
                </div>
            </div>
            <x-hearth-error class="field--error" for="meeting_types" />
        </div>

        <hr class="divider--thick" />

        <div class="flex gap-4">
            @if (!$meeting->id)
                <a class="cta secondary"
                    href="{{ localized_route('engagements.manage', $engagement) }}">{{ __('Cancel') }}</a>
            @endif
            <button>{{ $meeting->id ? __('Save meeting') : __('Add meeting') }}</button>
        </div>
    </form>
</x-app-layout>
