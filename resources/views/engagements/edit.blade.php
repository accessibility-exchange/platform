<x-app-medium-layout>
    <x-slot name="title">{{ __('Edit engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="{{ localized_route('projects.show', $engagement->project) }}">{{ $engagement->project->name }}</a>
            </li>
            <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1>
            {{ __('Edit engagement details') }}
        </h1>
        <p><a class="with-icon" href="{{ localized_route('engagements.edit-languages', $engagement) }}">
                @svg('heroicon-o-pencil', 'mr-1')
                {{ __('Edit page translations') }}
            </a>
        </p>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('engagements.update', $engagement) }}" method="POST" novalidate>
        @csrf
        @method('put')
        <hr class="divider--thick" />

        <h2>{{ __('Name') }}</h2>

        <x-translatable-input name="name" :label="__('What is the name of your engagement?') . ' ' . __('(required)')" :short-label="__('engagement name')" :model="$engagement" />
        <hr class="divider--thick" />

        <h2>{{ __('Description') }}</h2>

        <x-translatable-textarea name="description" :label="__('Please describe this engagement.') . ' ' . __('(required)')" :short-label="__('engagement description')" :hint="__(
            'This can include goals of your engagement, what topics you’ll cover, and what you’ll be asking participants to do.',
        )"
            :model="$engagement" />

        @if ($engagement->format === 'interviews')
            <hr class="divider--thick" />
            <h2>{{ __('Date range') }}</h2>
            <p>{{ __('Interviews can happen between the following dates:') }}</p>
            <x-date-input name="window_start_date" :label="__('Start date') . ' ' . __('(required)')" minimumYear="2022" :value="old('window_start_date', $engagement->window_start_date?->format('Y-m-d') ?? null)" />
            <x-date-input name="window_end_date" :label="__('End date') . ' ' . __('(required)')" minimumYear="2022" :value="old('window_end_date', $engagement->window_end_date?->format('Y-m-d') ?? null)" />

            <hr class="divider--thick" />
            <h2>{{ __('Ways to participate') }}</h2>
            <h3>{{ __('Real time interview') }}</h3>
            <h4>{{ __('Scheduling') }}</h4>
            <fieldset class="field stack">
                <legend>
                    {{ __('Between which times during the day will the interviews take place?') }}
                </legend>
                <div class="flex gap-6">
                    <div class="field @error('window_start_time') field--error @enderror">
                        <x-hearth-label for="window_start_time">{{ __('Start time') }}</x-hearth-label>
                        <x-hearth-input class="w-full" name="window_start_time" :value="old('window_start_time', $engagement->window_start_time?->format('G:i'))" hinted />
                        <x-hearth-hint for="window_start_time">{{ __('For example, 9:00') }}</x-hearth-hint>
                        <x-hearth-error for="window_start_time" />
                    </div>
                    <div class="field @error('window_end_time') field--error @enderror">
                        <x-hearth-label for="window_end_time">{{ __('End time') }}</x-hearth-label>
                        <x-hearth-input class="w-full" name="window_end_time" :value="old('window_end_time', $engagement->window_end_time?->format('G:i'))" hinted />
                        <x-hearth-hint for="window_end_time">{{ __('For example, 17:00') }}</x-hearth-hint>
                        <x-hearth-error for="window_end_time" />
                    </div>
                </div>
                <div class="field @error('timezone') field--error @enderror">
                    <x-hearth-label for="timezone">{{ __('Time zone') }}</x-hearth-label>
                    <x-hearth-select class="w-1/2" name="timezone" :options="$timezones" :selected="old('timezone', $engagement->timezone)" hinted />
                    <div class="field__hint" id="timezone-hint">
                        <p>{{ __('*Yukon and parts of British Columbia observe Mountain Standard Time all year.') }}
                        </p>
                        <p>{{ __('**Saskatchewan observes Central Standard Time all year.') }}</p>
                    </div>
                    <x-hearth-error for="timezone" />
                </div>
                <div class="field">
                    <x-hearth-checkbox name="window_flexibility" :checked="old('window_flexibility', $engagement->window_flexibility) == 1" />
                    <x-hearth-label for="window_flexibility">
                        {{ __('We can offer some time flexibility if it does not match with participants’ schedules.') }}
                    </x-hearth-label>
                </div>
            </fieldset>
            <h5 class="mt-12">{{ __('Which days of the week are available for interviews to be scheduled?') }}</h5>
            <div class="space-y-8">
                @foreach ($weekdays as $weekday)
                    <fieldset
                        class="field @error('weekday_availabilities.' . $weekday['value']) field--error @enderror">
                        <legend class="font-semibold">{{ $weekday['label'] }}</legend>
                        @foreach ($weekdayAvailabilities as $weekdayAvailability)
                            <div class="field">
                                <x-hearth-radio-button :name="'weekday_availabilities[' . $weekday['value'] . ']'" :id="$weekday['value'] . '-' . $weekdayAvailability['value']" :value="$weekdayAvailability['value']"
                                    :checked="old(
                                        'weekday_availabilities.' . $weekday['value'],
                                        $engagement->weekday_availabilities[$weekday['value']] ?? '',
                                    ) === $weekdayAvailability['value']" />
                                <x-hearth-label class="font-normal" :for="$weekday['value'] . '-' . $weekdayAvailability['value']">
                                    {{ $weekdayAvailability['label'] }}</x-hearth-label>
                            </div>
                        @endforeach
                        <x-hearth-error :for="'weekday_availabilities.' . $weekday['value']" />
                    </fieldset>
                @endforeach
            </div>
            <h4 class="mt-12">{{ __('Ways to attend') }}</h4>
            <div x-data="{ meetingTypes: {{ json_encode(old('meeting_types', $engagement->meeting_types ?? [])) }} }">
                <div class="field">
                    <x-hearth-checkbox id="meeting_types-in_person" name="meeting_types[]" value="in_person"
                        :checked="in_array('in_person', old('meeting_types', $engagement->meeting_types ?? []))" x-model="meetingTypes" />
                    <x-hearth-label for="meeting_types-in_person">{{ __('In person') }}</x-hearth-label>
                    <div class="box stack my-6" x-show="meetingTypes.includes('in_person')">
                        <div class="field @error('street_address') field--error @enderror">
                            <x-hearth-label for="street_address">{{ __('Street address') }}</x-hearth-label>
                            <x-hearth-input class="w-full" name="street_address" :value="old('street_address', $engagement->street_address)" required />
                            <x-hearth-error for="street_address" />
                        </div>
                        <div class="field @error('unit_suite_floor') field--error @enderror">
                            <x-hearth-label for="unit_suite_floor">{{ __('Unit, suite, or floor') }}</x-hearth-label>
                            <x-hearth-input class="w-full" name="unit_suite_floor" :value="old('unit_suite_floor', $engagement->unit_suite_floor)" required />
                            <x-hearth-error for="unit_suite_floor" />
                        </div>
                        <div class="field @error('locality') field--error @enderror">
                            <x-hearth-label for="locality">{{ __('City or town') }}</x-hearth-label>
                            <x-hearth-input class="w-full" name="locality" :value="old('locality', $engagement->locality)" required />
                            <x-hearth-error for="locality" />
                        </div>
                        <div class="field @error('region') field--error @enderror">
                            <x-hearth-label for="region">{{ __('Province or territory') }}</x-hearth-label>
                            <x-hearth-select name="region" :options="$regions" :selected="old('region', $engagement->region)" required />
                            <x-hearth-error for="region" />
                        </div>
                        <div class="field @error('postal_code') field--error @enderror">
                            <x-hearth-label for="postal_code">{{ __('Postal code') }}</x-hearth-label>
                            <x-hearth-input class="w-full" name="postal_code" :value="old('postal_code', $engagement->postal_code)" required />
                            <x-hearth-error for="postal_code" />
                        </div>

                        <x-translatable-textarea name="directions" :label="__('Further directions')" :short-label="__('further directions')"
                            :hint="__(
                                'Please be specific about where you would like the participants to go to participate in this engagement.',
                            )" :model="$engagement" />
                    </div>
                </div>
                <div class="field">
                    <x-hearth-checkbox id="meeting_types-web_conference" name="meeting_types[]" value="web_conference"
                        :checked="in_array('web_conference', old('meeting_types', $engagement->meeting_types ?? []))" x-model="meetingTypes" />
                    <x-hearth-label for="meeting_types-web_conference">{{ __('Virtual — video call') }}
                    </x-hearth-label>
                    <div class="box stack my-6" x-show="meetingTypes.includes('web_conference')">
                        <div class="field @error('meeting_software') field--error @enderror">
                            <x-hearth-label for="meeting_software">{{ __('Software') }}</x-hearth-label>
                            <x-hearth-hint for="meeting_software">
                                {{ __('E.g. Microsoft Teams, Webex, Zoom.') }}
                            </x-hearth-hint>
                            <x-hearth-input name="meeting_software" :value="old('meeting_software', $engagement->meeting_software)" hinted />
                            <x-hearth-error for="meeting_software" />
                        </div>
                        <div class="field @error('alternative_meeting_software') field--error @enderror">
                            <x-hearth-checkbox name="alternative_meeting_software" :checked="old('alternative_meeting_software', $engagement->alternative_meeting_software) ==
                                1" />
                            <x-hearth-label for="alternative_meeting_software">
                                {{ __('I can use other software if it is more accessible to participants') }}
                            </x-hearth-label>
                        </div>
                        <div class="field @error('meeting_url') field-error @enderror">
                            <x-hearth-label for="meeting_url" :value="__('Link to join')" />
                            <x-hearth-hint for="meeting_url">
                                {{ __('This will only be shared with participants who have accepted the invitation.') }}
                            </x-hearth-hint>
                            <x-hearth-input name="meeting_url" type="url" :value="old('meeting_url', $engagement->meeting_url)" hinted />
                            <x-hearth-error for="meeting_url" />
                        </div>
                        <x-translatable-textarea name="additional_video_information" :label="__('Additional information to join')"
                            :short-label="__('additional information to join')" :hint="__(
                                'For example, Meeting password, meeting ID. This will be shared only with participants who have accepted the invitation.',
                            )" :model="$engagement" />
                    </div>
                </div>
                <div class="field">
                    <x-hearth-checkbox id="meeting_types-phone" name="meeting_types[]" value="phone"
                        :checked="in_array('phone', old('meeting_types', $engagement->meeting_types ?? []))" x-model="meetingTypes" />
                    <x-hearth-label for="meeting_types-phone">{{ __('Virtual — phone call') }}</x-hearth-label>
                    <div class="box stack my-6" x-show="meetingTypes.includes('phone')">
                        <div class="field @error('meeting_phone') field-error @enderror">
                            <x-hearth-label for="meeting_phone" :value="__('Phone number to join')" />
                            <x-hearth-hint for="meeting_phone">
                                {{ __('This will only be shared with participants who have accepted the invitation.') }}
                            </x-hearth-hint>
                            <x-hearth-input name="meeting_phone" type="tel" :value="old('meeting_phone', $engagement->meeting_phone?->formatForCountry('CA'))" hinted />
                            <x-hearth-error for="meeting_phone" />
                        </div>
                        <x-translatable-textarea name="additional_phone_information" :label="__('Additional information to join')"
                            :short-label="__('additional information to join')" :hint="__(
                                'For example, Meeting password, meeting ID. This will be shared only with participants who have accepted the invitation.',
                            )" :model="$engagement" />
                    </div>
                </div>
            </div>
            <hr class="divider--thick" />
            <h3>{{ __('Written or recorded responses') }}</h3>
            <p>{{ __('Some participants may not be able to meet in real-time. For them, you can send out a list of questions, and participants can respond to them in formats you accept.') }}
            </p>
            <h4>{{ __('Dates') }}</h4>
            <x-date-input name="materials_by_date" :label="__('Questions are sent to participants by:')" minimumYear="2022" :value="old('materials_by_date', $engagement->materials_by_date?->format('Y-m-d') ?? null)" />
            <x-date-input name="complete_by_date" :label="__('Responses are due by:')" minimumYear="2022" :value="old('complete_by_date', $engagement->complete_by_date?->format('Y-m-d') ?? null)" />
            <fieldset
                class="field @error('accepted_formats') field--error @enderror @error('other_accepted_formats') field--error @enderror @error('other_accepted_format') field--error @enderror stack"
                x-data="{ otherAcceptedFormats: {{ old('other_accepted_formats', !empty($engagement->other_accepted_format)) ? 1 : 'null' }} }">
                <legend>{{ __('Accepted formats') }}</legend>
                <x-hearth-checkboxes name="accepted_formats" :options="$formats" :checked="old('accepted_formats', $engagement->accepted_formats ?? [])" required />
                <x-hearth-error for="accepted_formats" />
                <div class="field">
                    <x-hearth-checkbox name="other_accepted_formats" :checked="old('other_accepted_formats', !empty($engagement->other_accepted_format))"
                        x-model="otherAcceptedFormats" />
                    <x-hearth-label for="other_accepted_formats">{{ __('Other') }}</x-hearth-label>
                    <x-hearth-error for="other_accepted_formats" />
                </div>
                <div class="field__subfield stack">
                    <x-translatable-input name="other_accepted_format" :label="__('Other accepted format')" :short-label="__('other accepted format')"
                        :model="$engagement" x-show="otherAcceptedFormats" />
                </div>
            </fieldset>
            <div class="field @error('open_to_other_formats') field--error @enderror">
                <x-hearth-checkbox name="open_to_other_formats" :checked="old(
                    'open_to_other_formats',
                    $engagement->extra_attributes->get('open_to_other_formats', 0),
                ) == 1" />
                <x-hearth-label for="open_to_other_formats">
                    {{ __('I am open to other formats suggested by participants') }}
                </x-hearth-label>
            </div>
        @endif

        @if (in_array($engagement->format, ['survey', 'other-async']))
            <hr class="divider--thick" />
            <h2>{{ $engagement->format === 'survey' ? __('Survey materials') : __('Materials') }}
            </h2>
            <h3>{{ __('Date') }}</h3>
            <x-date-input name="materials_by_date" :label="__('Materials are sent to participants by') . ' ' . __('(required)') . ':'" minimumYear="2022" :value="old('materials_by_date', $engagement->materials_by_date?->format('Y-m-d') ?? null)" />
            <x-date-input name="complete_by_date" :label="__('Completed materials are due by') . ' ' . __('(required)') . ':'" minimumYear="2022" :value="old('complete_by_date', $engagement->complete_by_date?->format('Y-m-d') ?? null)" />
            <hr />
            <fieldset class="field @error('document_languages') field--error @enderror">
                <legend>
                    <h3>{{ __('Languages') . ' ' . __('(required)') }}</h3>
                </legend>
                <x-hearth-hint for="document_languages">
                    {{ __('Please indicate the languages to be used for this engagement’s documents.') }}
                </x-hearth-hint>
                <livewire:language-picker name="document_languages" :languages="old(
                    'document_languages',
                    !empty($engagement->document_languages) ? $engagement->document_languages : [],
                )" :availableLanguages="$languages"
                    hinted="document_languages-hint" />
                <x-hearth-error for="document_languages" />
            </fieldset>
        @endif

        @if (class_basename($engagement->project->projectable) === 'Organization')
            <hr class="divider--thick" />
            <h2>{{ __('Payment') }}</h2>
            <div class="field @error('paid') field--error @enderror">
                <x-hearth-label for="paid">{{ __('Is this engagement paid or volunteer?') }}</x-hearth-label>
                <x-hearth-radio-buttons name="paid" :options="[['value' => '1', 'label' => __('Paid')], ['value' => '0', 'label' => __('Volunteer')]]" :checked="old('paid', $engagement->paid ?? 1)" hinted />
                <x-hearth-error for="paid" />
            </div>
        @endif

        @if ($engagement->who === 'individuals')
            <hr class="divider--thick" />
            <h2>{{ __('Sign up deadline') }}</h2>

            <div class="field @error('signup_by_date') field--error @enderror">
                <x-date-input name="signup_by_date" :label="$engagement->recruitment === 'open'
                    ? __('Participants must sign up for this engagement by the following date') .
                        ' ' .
                        __('(required)') .
                        ':'
                    : __('Participants must respond to their invitation by the following date') .
                        ' ' .
                        __('(required)') .
                        ':'" :minimumYear="date('Y')" :value="old('signup_by_date', $engagement->signup_by_date?->format('Y-m-d') ?? null)" />
            </div>
        @endif
        <hr class="divider--thick" />

        <div class="flex gap-4">
            <button>{{ __('Save') }}</button>
            @if ($engagement->checkStatus('draft'))
                <button class="secondary" name="publish" value="1"
                    @if (!$engagement->isPublishable()) @ariaDisabled @endif>{{ __('Publish') }}</button>
            @endif
        </div>
        @if (!$engagement->hasEstimateAndAgreement())
            {!! Str::markdown(
                __(
                    'You must [approve your estimate and return your signed agreement](:estimates_and_agreements) before you can publish your engagement.',
                    ['estimates_and_agreements' => localized_route('projects.manage-estimates-and-agreements', $project)],
                ),
            ) !!}
        @else
            <p>{{ __('Once you publish your engagement details, anyone on this website will be able to access it.') }}
            </p>
        @endif
    </form>
</x-app-medium-layout>
