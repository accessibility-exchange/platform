<form action="{{ localized_route('individuals.update-communication-and-consultation-preferences', $individual) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    <div class="with-sidebar with-sidebar:last">
        @include('individuals.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => $individual->isConnector() ? 5 : 4]) }}<br />
                {{ __('Communication and meeting preferences') }}
            </h2>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and previous') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
            </p>

            <div class="stack" x-data="{contactPerson: '{{ old('preferred_contact_person', $individual->user->preferred_contact_person ?? 'me') }}'}">
                <fieldset>
                    <legend>{{ __('Contact person (required)') }}</legend>

                    <x-hearth-radio-buttons name="preferred_contact_person" :options="[['value' => 'me', 'label' => __('Me')], ['value' => 'support-person', 'label' => __('My support person')]]" :checked="old('preferred_contact_person', $individual->user->preferred_contact_person ?? 'me')" x-model="contactPerson" />
                </fieldset>

                <fieldset x-show="contactPerson == 'me'">
                    <legend x-text="contactPerson == 'me' ? '{{ __('My contact information (required)') }}' : '{{ __('My contact information (optional)') }}'">{{ __('My contact information (required)') }}</legend>

                    <div class="field @error('email') field-error @enderror">
                        <x-hearth-label for="email" :value="__('Email')" />
                        <x-hearth-input type="email" name="email" :value="old('email', !empty($individual->user->email) ? $individual->user->email : $individual->user->email)" />
                        <x-hearth-hint for="email">{{ __('If you change your email address here, you will need to sign in to this website using the new email address.') }}</x-hearth-hint>
                        <x-hearth-error for="email" />
                    </div>
                    <div class="field @error('phone') field-error @enderror">
                        <x-hearth-label for="phone" :value="__('Phone number')" />
                        <x-hearth-input type="tel" name="phone" :value="old('phone', $individual->user->phone ?  $individual->user->phone->formatForCountry('CA') : '')" wire:model.lazy="phone" />
                        <x-hearth-error for="phone" />
                    </div>

                    <div class="field @error('vrs') field-error @enderror">
                        <x-hearth-checkbox name="vrs" :checked="old('vrs', $individual->user->vrs ?? false)" wire:model="vrs" />
                        <x-hearth-label for="vrs" :value="__('I require Video Relay Service (VRS) for phone calls')" />
                        <x-hearth-error for="vrs" />
                    </div>
                </fieldset>

                <fieldset x-show="contactPerson == 'support-person'">
                    <legend x-text="contactPerson == 'support-person' ? '{{ __('My support person’s contact information (required)') }}' : '{{ __('My support person’s contact information (optional)') }}'">{{ __('My support person’s contact information (optional)') }}</legend>
                    <div class="field @error("support_person_name") field-error @enderror">
                        <x-hearth-label for="support_person_name" :value="__('Contact name')" />
                        <x-hearth-hint for="support_person_name">{{ __('This does not have to be their legal name.') }}</x-hearth-hint>
                        <x-hearth-input id="support_person_name" name="support_person_name" :value="old('support_person_name', $individual->user->support_person_name)" required hinted />
                        <x-hearth-error for="support_person_name" field="support_person_name" />
                    </div>
                    <div class="field @error('support_person_email') field-error @enderror">
                        <x-hearth-label for="support_person_email" :value="__('Email')" />
                        <x-hearth-input type="email" name="support_person_email" :value="old('support_person_email', $individual->user->support_person_email)" />
                        <x-hearth-error for="support_person_email" />
                    </div>
                    <div class="field @error('support_person_phone') field-error @enderror">
                        <x-hearth-label for="support_person_phone" :value="__('Phone number')" />
                        <x-hearth-input type="tel" name="support_person_phone" :value="old('support_person_phone', $individual->user->support_person_phone ?  $individual->user->support_person_phone->formatForCountry('CA') : '')" />
                        <x-hearth-error for="support_person_phone" />
                    </div>

                    <div class="field @error('support_person_vrs') field-error @enderror">
                        <x-hearth-checkbox name="support_person_vrs" :checked="old('support_person_vrs', $individual->user->support_person_vrs ?? false)" />
                        <x-hearth-label for="support_person_vrs" :value="__('My support person requires Video Relay Service (VRS) for phone calls')" />
                        <x-hearth-error for="support_person_vrs" />
                    </div>
                </fieldset>

                <div class="field @error('preferred_contact_method') field-error @enderror">
                    <x-hearth-label for="preferred_contact_method">{{ __('Preferred contact method (required)') }}</x-hearth-label>
                    <x-hearth-select name="preferred_contact_method" :options="Spatie\LaravelOptions\Options::forArray(['email' => __('Email'), 'phone' => __('Phone')])->toArray()" :selected="old('preferred_contact_method', $individual->user->preferred_contact_method ?? 'email')"/>
                    <x-hearth-error for="preferred_contact_method" />
                </div>
            </div>

            <fieldset class="field @error('meeting_types') field--error @enderror">
                <legend>{{ __('What types of meetings are you able to attend? (required)') }}</legend>
                <x-hearth-checkboxes name="meeting_types" :options="$meetingTypes" :checked="old('meeting_types', $individual->meeting_types ?? [])" />
                <x-hearth-error for="meeting_types" />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and previous') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
            </p>
        </div>
    </div>
</form>
