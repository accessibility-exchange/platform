<form action="{{ localized_route('community-members.update-communication-and-meeting-preferences', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    <div class="with-sidebar with-sidebar:last">
        @include('community-members.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
                {{ __('Communication and meeting preferences') }}
            </h2>

            <p class="repel">
                <button class="secondary" name="save_and_previous">{{ __('Save and previous') }}</button>
                <button name="save">{{ __('Save') }}</button>
            </p>

            <div class="stack" x-data="{contactPerson: '{{ old('preferred_contact_person', $communityMember->preferred_contact_person ?? 'me') }}'}">
                <fieldset>
                    <legend>{{ __('Contact person (required)') }}</legend>

                    <x-hearth-radio-buttons name="preferred_contact_person" :options="['me' => __('Me'), 'support-person' => __('My support person')]" :checked="old('preferred_contact_person', $communityMember->preferred_contact_person ?? 'me')" x-model="contactPerson" />
                </fieldset>

                <fieldset x-show="contactPerson == 'me'">
                    <legend x-text="contactPerson == 'me' ? '{{ __('My contact information (required)') }}' : '{{ __('My contact information (optional)') }}'">{{ __('My contact information (required)') }}</legend>

                    <div class="field @error('email') field-error @enderror">
                        <x-hearth-label for="email" :value="__('Email')" />

                        <x-hearth-input type="email" name="email" :value="old('email', $communityMember->email ?? $communityMember->user->email)" />
                        <x-hearth-error for="email" />
                    </div>
                    <div class="field @error('phone') field-error @enderror">
                        <x-hearth-label for="phone" :value="__('Phone number')" />
                        <x-hearth-input type="tel" name="phone" :value="old('phone', $communityMember->phone)" wire:model.lazy="phone" />
                        <x-hearth-error for="phone" />
                    </div>

                    <div class="field @error('vrs') field-error @enderror">
                        <x-hearth-checkbox name="vrs" :checked="old('vrs', $communityMember->vrs ?? false)" wire:model="vrs" />
                        <x-hearth-label for="vrs" :value="__('I require Video Relay Service (VRS) for phone calls')" />
                        <x-hearth-error for="vrs" />
                    </div>
                </fieldset>

                <fieldset x-show="contactPerson == 'support-person'">
                    <legend x-text="contactPerson == 'support-person' ? '{{ __('My support person’s contact information (required)') }}' : '{{ __('My support person’s contact information (optional)') }}'">{{ __('My support person’s contact information (optional)') }}</legend>
                    <div class="field @error("support_person_name") field-error @enderror">
                        <x-hearth-label for="support_person_name" :value="__('Contact name')" />
                        <x-hearth-hint for="support_person_name">{{ __('This does not have to be their legal name.') }}</x-hearth-hint>
                        <x-hearth-input id="support_person_name" name="support_person_name" :value="old('support_person_name', $communityMember->support_person_name)" required hinted />
                        <x-hearth-error for="support_person_name" field="support_person_name" />
                    </div>
                    <div class="field @error('support_person_email') field-error @enderror">
                        <x-hearth-label for="support_person_email" :value="__('Email')" />
                        <x-hearth-input type="email" name="support_person_email" :value="old('support_person_email', $communityMember->support_person_email)" />
                        <x-hearth-error for="support_person_email" />
                    </div>
                    <div class="field @error('support_person_phone') field-error @enderror">
                        <x-hearth-label for="support_person_phone" :value="__('Phone number')" />
                        <x-hearth-input type="tel" name="support_person_phone" :value="old('support_person_phone', $communityMember->support_person_phone)" />
                        <x-hearth-error for="support_person_phone" />
                    </div>

                    <div class="field @error('support_person_vrs') field-error @enderror">
                        <x-hearth-checkbox name="support_person_vrs" :checked="old('support_person_vrs', $communityMember->support_person_vrs ?? false)" />
                        <x-hearth-label for="support_person_vrs" :value="__('My support person requires Video Relay Service (VRS) for phone calls')" />
                        <x-hearth-error for="support_person_vrs" />
                    </div>
                </fieldset>

                <div class="field @error('preferred_contact_method') field-error @enderror">
                    <x-hearth-label for="preferred_contact_method">{{ __('Preferred contact method (required)') }}</x-hearth-label>
                    <x-hearth-select name="preferred_contact_method" :options="['email' => __('Email'), 'phone' => __('Phone')]" :selected="old('preferred_contact_method', $communityMember->preferred_contact_method ?? 'email')"/>
                    <x-hearth-error for="preferred_contact_method" />
                </div>
            </div>

            <fieldset class="field @error('meeting_types') field--error @enderror">
                <legend>{{ __('What types of meetings are you able to attend? (required)') }}</legend>
                <x-hearth-checkboxes name="meeting_types" :options="$meetingTypes" :checked="old('meeting_types', $communityMember->meeting_types ?? [])" />
                <x-hearth-error for="meeting_types" />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="save_and_previous">{{ __('Save and previous') }}</button>
                <button name="save">{{ __('Save') }}</button>
            </p>
        </div>
    </div>
</form>
