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
                <x-heroicon-o-pencil class="mr-2 h-5 w-5" role="presentation" aria-hidden="true" />
                {{ __('Edit page translations') }}
            </a>
        </p>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack"
        action="{{ localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]) }}"
        method="POST" novalidate>
        @csrf
        @method('put')

        <h2>{{ __('Name') }}</h2>

        <x-translatable-input name="name" :label="__('What is the name of your engagement?')" :short-label="__('engagement name')" :model="$engagement" />

        <h2>{{ __('Description') }}</h2>

        <x-translatable-textarea name="description" :label="__('Please describe this engagement.')" :short-label="__('engagement description')" :hint="__(
            'This can include goals of your engagement, what topics you’ll cover, and what you’ll be asking participants to do.',
        )"
            :model="$engagement" />

        @if ($engagement->format === 'interviews')
            <h2>{{ __('Date range') }}</h2>
            <p>{{ __('Interviews can happen between the following dates:') }}</p>
            <livewire:date-picker name="window_start_date" :label="__('Start date')" minimumYear="2022" :value="old('window_start_date', $project->window_start_date?->format('Y-m-d') ?? null)" />
            <livewire:date-picker name="window_end_date" :label="__('End date')" minimumYear="2022" :value="old('window_end_date', $project->window_end_date?->format('Y-m-d') ?? null)" />

            <h2>{{ __('Ways to participate') }}</h2>
            <h3>{{ __('Real time interview') }}</h3>
            <h4>{{ __('Scheduling') }}</h4>
            <fieldset class="field stack">
                <legend>{{ __('Between which times during the day will the interviews take place?') }}</legend>
                <div class="flex gap-6">
                    <div class="field @error('window_start_time') field--error @enderror">
                        <x-hearth-label for="window_start_time">{{ __('Start time') }}</x-hearth-label>
                        <x-hearth-input class="w-full" name="window_start_time" :value="old(
                            'window_start_time',
                            $engagement->extra_attributes->get('window_start_time', ''),
                        )" hinted />
                        <x-hearth-hint for="window_start_time">{{ __('For example, 9:00am') }}</x-hearth-hint>
                        <x-hearth-error for="window_start_time" />
                    </div>
                    <div class="field @error('window_end_time') field--error @enderror">
                        <x-hearth-label for="window_end_time">{{ __('End time') }}</x-hearth-label>
                        <x-hearth-input class="w-full" name="window_end_time" :value="old('window_end_time', $engagement->extra_attributes->get('window_end_time', ''))" hinted />
                        <x-hearth-hint for="window_end_time">{{ __('For example, 5:00pm') }}</x-hearth-hint>
                        <x-hearth-error for="window_end_time" />
                    </div>
                </div>
                <div class="field @error('timezone') field--error @enderror">
                    <x-hearth-label for="timezone">{{ __('Time zone') }}</x-hearth-label>
                    <x-hearth-select name="timezone" :options="$timezones" :selected="old('timezone', $engagement->timezone)" hinted />
                    <div class="field__hint" id="timezone-hint">
                        <p>{{ __('*Yukon and parts of British Columbia observe Mountain Standard Time all year.') }}
                        </p>
                        <p>{{ __('**Saskatchewan observes Central Standard Time all year.') }}</p>
                    </div>
                    <x-hearth-error for="timezone" />
                </div>
                <div class="field">
                    <x-hearth-checkbox name="window_flexibility" />
                    <x-hearth-label for="window_flexibility">
                        {{ __('We can offer some time flexibility if it does not match with participants’ schedules.') }}
                    </x-hearth-label>
                </div>
                {{-- TODO: add days available (see https://www.figma.com/file/AXufBnFAVHvGdsxnQMVhNF/The-Accessibility-Exchange-Wireframes?node-id=2628%3A68261)
                    Store in extra_attributes column (see https://github.com/spatie/laravel-schemaless-attributes) --}}
            </fieldset>
            <h4>{{ __('Ways to attend') }}</h4>
            <div x-data="{
                @foreach ($meetingTypes as $type)
                '{{ $type['value'] }}': {{ old('meeting_types.' . $type['value'], $engagement->extra_attributes->get('meeting_types', 0)) }}@if (!$loop->last),@endif @endforeach
            }">
                <div class="field">
                    <x-hearth-checkbox id="meeting-types-in-person" :name="'meeting_types[in_person]'" :checked="old(
                        'meeting_types.in_person',
                        $engagement->extra_attributes->get('meeting_types.in_person', 0),
                    ) == 1"
                        x-model="in_person" />
                    <x-hearth-label for="meeting-types-in-person">{{ __('In person') }}</x-hearth-label>
                    <div x-show="in_person">
                        {{-- TODO: Add in-person interview details (see https://www.figma.com/file/AXufBnFAVHvGdsxnQMVhNF/The-Accessibility-Exchange-Wireframes?node-id=2628%3A68261)
                            Store in extra_attributes column (see https://github.com/spatie/laravel-schemaless-attributes) --}}
                    </div>
                </div>
                <div class="field">
                    <x-hearth-checkbox id="meeting-types-web-conference" :name="'meeting_types[web_conference]'" :checked="old(
                        'meeting_types.web_conference',
                        $engagement->extra_attributes->get('meeting_types.web_conference', 0),
                    ) == 1"
                        x-model="web_conference" />
                    <x-hearth-label for="meeting-types-web-conference">{{ __('Virtual — video call') }}
                    </x-hearth-label>
                    <div x-show="web_conference">
                        {{-- TODO: Add web conference details (see https://www.figma.com/file/AXufBnFAVHvGdsxnQMVhNF/The-Accessibility-Exchange-Wireframes?node-id=2628%3A68261)
                            Store in extra_attributes column (see https://github.com/spatie/laravel-schemaless-attributes) --}}
                    </div>
                </div>
                <div class="field">
                    <x-hearth-checkbox id="meeting-types-phone" :name="'meeting_types[phone]'" :checked="old(
                        'meeting_types.phone',
                        $engagement->extra_attributes->get('meeting_types.phone', 0),
                    ) == 1" x-model="phone" />
                    <x-hearth-label for="meeting-types-phone">{{ __('Virtual — phone call') }}</x-hearth-label>
                    <div x-show="phone">
                        {{-- TODO: Add phone call details (see https://www.figma.com/file/AXufBnFAVHvGdsxnQMVhNF/The-Accessibility-Exchange-Wireframes?node-id=2628%3A68261)
                            Store in extra_attributes column (see https://github.com/spatie/laravel-schemaless-attributes) --}}
                    </div>
                </div>
                <h3>{{ __('Written or recorded responses') }}</h3>
                <p>{{ __('Some participants may not be able to meet in real-time. For them, you can send out a list of questions, and participants can respond to them in formats you accept.') }}
                </p>
                <h4>{{ __('Dates') }}</h4>
                <livewire:date-picker name="materials_by_date" :label="__('Questions are sent to participants by:')" minimumYear="2022"
                    :value="old('window_start_date', $project->window_start_date?->format('Y-m-d') ?? null)" />
                <livewire:date-picker name="complete_by_date" :label="__('Responses are due by:')" minimumYear="2022"
                    :value="old('window_end_date', $project->window_end_date?->format('Y-m-d') ?? null)" />
                {{-- TODO: Add accepted formats (see https://www.figma.com/file/AXufBnFAVHvGdsxnQMVhNF/The-Accessibility-Exchange-Wireframes?node-id=2628%3A68261)
                     Store in accepted_formats column (see https://github.com/spatie/laravel-schemaless-attributes)
                    <fieldset class="field @error('accepted_formats') field--error @enderror">
                        <legend>{{ __('Accepted formats') }}</legend>
                    </fieldset> --}}
            </div>
        @endif

        @if (in_array($engagement->format, ['focus-group', 'workshop', 'other-sync']))
            <h2>{{ __('Meetings') }}</h2>
            <p>
                <a class="cta secondary" href="#TODO">{{ __('Add new meeting') }}</a>
            </p>
        @endif

        @if (class_basename($engagement->project->projectable) === 'Organization')
            <h2>{{ __('Payment') }}</h2>
            <div class="field @error('paid') field--error @enderror">
                <x-hearth-label for="paid">{{ __('Is this engagement paid or volunteer?') }}</x-hearth-label>
                <x-hearth-radio-buttons name="paid" :options="[['value' => '1', 'label' => __('Paid')], ['value' => '0', 'label' => __('Volunteer')]]" :checked="old('paid', $engagement->paid ?? 1)" hinted />
                <x-hearth-error for="paid" />
            </div>
        @endif

        <h2>{{ __('Sign up deadline') }}</h2>

        <div class="field @error('signup_by_date') field--error @enderror">
            <livewire:date-picker name="signup_by_date" :label="__('Please respond to your invitation to participate by:')" :minimumYear="date('Y')" :value="old('signup_by_date', $engagement->signup_by_date?->format('Y-m-d') ?? null)" />
        </div>

        <div class="flex gap-4">
            <button>{{ __('Save') }}</button>
            @if ($engagement->isPublishable())
                <button class="secondary" name="publish">{{ __('Publish') }}</button>
            @endif
        </div>
        <p>{{ __('Once you publish your engagement details, anyone on this website will be able to access it.') }}</p>
    </form>
</x-app-medium-layout>
