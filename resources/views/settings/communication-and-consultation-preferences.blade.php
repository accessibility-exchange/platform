<x-app-layout>
    <x-slot name="title">{{ __('Communication and consultation preferences') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Communication and consultation preferences') }}
        </h1>
    </x-slot>

    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('settings.update-communication-and-consultation-preferences') }}"
        novalidate method="post">
        @csrf
        @method('put')

        <h2>{{ __('Communication') }}</h2>

        <div class="stack" x-data="{ contactPerson: '{{ old('preferred_contact_person', $individual->user->preferred_contact_person ?? 'me') }}' }">
            <fieldset>
                <legend>{{ __('Contact person (required)') }}</legend>

                <x-hearth-radio-buttons name="preferred_contact_person" :options="[
                    ['value' => 'me', 'label' => __('Me')],
                    ['value' => 'support-person', 'label' => __('My support person')],
                ]" :checked="old('preferred_contact_person', $individual->user->preferred_contact_person ?? 'me')"
                    x-model="contactPerson" />
            </fieldset>

            <fieldset x-show="contactPerson == 'me'">
                <legend
                    x-text="contactPerson == 'me' ? '{{ __('My contact information (required)') }}' : '{{ __('My contact information (optional)') }}'">
                    {{ __('My contact information (required)') }}</legend>

                <div class="field @error('email') field-error @enderror">
                    <x-hearth-label for="email" :value="__('Email')" />
                    <x-hearth-input name="email" type="email" :value="old('email', $individual->user->email)" />
                    <x-hearth-hint for="email">
                        {{ __('This is also the email you use to sign in to this account. If you change this, you are also changing your sign in email.') }}
                    </x-hearth-hint>
                    <x-hearth-error for="email" />
                </div>
                <div class="field @error('phone') field-error @enderror">
                    <x-hearth-label for="phone" :value="__('Phone number')" />
                    <x-hearth-input name="phone" type="tel" :value="old(
                        'phone',
                        $individual->user->phone ? $individual->user->phone->formatForCountry('CA') : '',
                    )" wire:model.lazy="phone" />
                    <x-hearth-error for="phone" />
                </div>

                <div class="field @error('vrs') field-error @enderror">
                    <x-hearth-checkbox name="vrs" :checked="old('vrs', $individual->user->vrs ?? false)" wire:model="vrs" />
                    <x-hearth-label for="vrs" :value="__('I require Video Relay Service (VRS) for phone calls')" />
                    <x-hearth-error for="vrs" />
                </div>
            </fieldset>

            <fieldset x-show="contactPerson == 'support-person'">
                <legend
                    x-text="contactPerson == 'support-person' ? '{{ __('My support person’s contact information (required)') }}' : '{{ __('My support person’s contact information (optional)') }}'">
                    {{ __('My support person’s contact information (optional)') }}</legend>
                <div class="field @error('support_person_name') field-error @enderror">
                    <x-hearth-label for="support_person_name" :value="__('Contact name')" />
                    <x-hearth-hint for="support_person_name">{{ __('This does not have to be their legal name.') }}
                    </x-hearth-hint>
                    <x-hearth-input id="support_person_name" name="support_person_name" :value="old('support_person_name', $individual->user->support_person_name)" required
                        hinted />
                    <x-hearth-error for="support_person_name" field="support_person_name" />
                </div>
                <div class="field @error('support_person_email') field-error @enderror">
                    <x-hearth-label for="support_person_email" :value="__('Email')" />
                    <x-hearth-input name="support_person_email" type="email" :value="old('support_person_email', $individual->user->support_person_email)" />
                    <x-hearth-error for="support_person_email" />
                </div>
                <div class="field @error('support_person_phone') field-error @enderror">
                    <x-hearth-label for="support_person_phone" :value="__('Phone number')" />
                    <x-hearth-input name="support_person_phone" type="tel" :value="old(
                        'support_person_phone',
                        $individual->user->support_person_phone
                            ? $individual->user->support_person_phone->formatForCountry('CA')
                            : '',
                    )" />
                    <x-hearth-error for="support_person_phone" />
                </div>

                <div class="field @error('support_person_vrs') field-error @enderror">
                    <x-hearth-checkbox name="support_person_vrs" :checked="old('support_person_vrs', $individual->user->support_person_vrs ?? false)" />
                    <x-hearth-label for="support_person_vrs" :value="__('My support person requires Video Relay Service (VRS) for phone calls')" />
                    <x-hearth-error for="support_person_vrs" />
                </div>
            </fieldset>

            <div class="field @error('preferred_contact_method') field-error @enderror">
                <x-hearth-label for="preferred_contact_method">{{ __('Preferred contact method (required)') }}
                </x-hearth-label>
                <x-hearth-select name="preferred_contact_method" :options="Spatie\LaravelOptions\Options::forArray([
                    'email' => __('Email'),
                    'phone' => __('Phone'),
                ])->toArray()" :selected="old('preferred_contact_method', $individual->user->preferred_contact_method ?? 'email')" />
                <x-hearth-error for="preferred_contact_method" />
            </div>
        </div>

        @if ($individual->isParticipant())
            <div class="stack" x-data="{ consultingMethods: {{ json_encode(array_map(fn($value): int => (int) $value, old('consulting_methods', $selectedConsultingMethods ?? '[]'))) }} }">
                <h2>{{ __('Consultations') }}</h2>

                <fieldset class="field @error('consulting_methods') field--error @enderror">
                    <legend>{{ __('Please indicate the types of consultations you are willing to do (required)') }}
                    </legend>
                    <x-hearth-checkboxes name="consulting_methods" :options="$consultingMethods" :checked="old('consulting_methods', $selectedConsultingMethods)"
                        x-model.number="consultingMethods" />
                    <x-hearth-error for="consulting_methods" />
                </fieldset>

                <fieldset class="field @error('meeting_types') field--error @enderror"
                    x-show="consultingMethods.includes({{ $interviews }}) || consultingMethods.includes({{ $focusGroups }}) || consultingMethods.includes({{ $workshops }})"
                    x-cloak>
                    <legend>{{ __('Please indicate the types of meetings you are willing to attend (required)') }}
                    </legend>
                    <x-hearth-checkboxes name="meeting_types" :options="$meetingTypes" :checked="old('meeting_types', $individual->meeting_types ?? [])" />
                    <x-hearth-error for="meeting_types" />
                </fieldset>
            </div>
        @endif

        <button>{{ __('Save') }}</button>
    </form>

</x-app-layout>
