<form class="stack" action="{{ localized_route('projects.update-team', $project) }}" method="POST"
    enctype="multipart/form-data" novalidate>
    @csrf
    @method('put')

    <div class="with-sidebar with-sidebar:last">

        @include('projects.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Project team') }}
            </h2>

            <h3>{{ __('About your team') }}</h3>

            <div class="field @error('team_size') field--error @enderror stack">
                <x-translatable-input name="team_size" :label="__('Please indicate the number of people on your team.') . ' ' . __('(optional)')" :shortLabel="__('the number of people on your team')" :hint="__('You can provide either an exact number or a range.')"
                    :model="$project" />
            </div>

            <fieldset class="field @error('team_has_disability_or_deaf_lived_experience') field--error @enderror stack">
                <legend>
                    {{ __('Please indicate whether any member of your team has lived/living experiences of disability or being Deaf.') . ' ' . __('(optional)') }}
                </legend>
                <x-hearth-radio-buttons name="team_has_disability_or_deaf_lived_experience" :options="Spatie\LaravelOptions\Options::forArray([1 => __('Yes'), 0 => __('No')])->toArray()"
                    :checked="old(
                        'team_has_disability_or_deaf_lived_experience',
                        $project->team_has_disability_or_deaf_lived_experience ?? '',
                    )" />
            </fieldset>

            <fieldset class="field stack">
                <legend>
                    <h3>{{ __('Training your team has received') . ' ' . __('(optional)') }}</h3>
                </legend>
                <p class="field__hint">
                    {{ __('Please list any training related to accessibility or inclusion that your team members have received.') }}
                </p>
                <livewire:team-trainings :trainings="old('team_trainings', $project->team_trainings ?? [])" />
            </fieldset>

            <fieldset class="field stack">
                <legend>
                    <h3>{{ __('Team contact') }}</h3>
                </legend>
                <p class="field__hint">
                    {{ __('Please provide the details for a member of your team whom potential participants may contact to ask questions.') }}
                </p>

                <div class="field @error('contact_person_name') field-error @enderror">
                    <x-hearth-label for="contact_person_name" :value="__('Name') . ' ' . __('(required)')" />
                    <x-hearth-input id="contact_person_name" name="contact_person_name" :value="old('contact_person_name', $project->contact_person_name)" required
                        hinted />
                    <x-hearth-error for="contact_person_name" field="contact_person_name" />
                </div>

                <div class="field @error('contact_person_email') field-error @enderror">
                    <x-hearth-label for="contact_person_email" :value="__('Email') . ' ' . __('(required)')" />
                    <x-hearth-input name="contact_person_email" type="email" :value="old('contact_person_email', $project->contact_person_email)" />
                    <x-hearth-error for="contact_person_email" />
                </div>

                <div class="field @error('contact_person_phone') field-error @enderror">
                    <x-hearth-label for="contact_person_phone" :value="__('Phone number')" />
                    <x-hearth-input name="contact_person_phone" type="tel" :value="old('contact_person_phone', $project->contact_person_phone?->formatForCountry('CA'))" />
                    <x-hearth-error for="contact_person_phone" />
                </div>

                <div class="field @error('contact_person_vrs') field-error @enderror">
                    <x-hearth-checkbox name="contact_person_vrs" :checked="old('contact_person_vrs', $project->contact_person_vrs ?? false)" />
                    <x-hearth-label for="contact_person_vrs" :value="__('They require Video Relay Service (VRS) for phone calls')" />
                    <x-hearth-error for="contact_person_vrs" />
                </div>

                <div class="field @error('preferred_contact_method') field-error @enderror">
                    <x-hearth-label for="preferred_contact_method">{{ __('Preferred contact method') }}
                    </x-hearth-label>
                    <x-hearth-select name="preferred_contact_method" :options="Spatie\LaravelOptions\Options::forArray([
                        'email' => __('Email'),
                        'phone' => __('Phone'),
                    ])->toArray()" :selected="old('preferred_contact_method', $project->preferred_contact_method ?? 'email')" />
                    <x-hearth-error for="preferred_contact_method" />
                </div>

                <div class="field @error('contact_person_response_time') field-error @enderror">
                    <x-translatable-input name="contact_person_response_time" :label="__('Approximate response time') . ' ' . __('(required)')" :hint="__('For example, three to five business days, within one hour')"
                        :shortLabel="__('approximate response time')" :model="$project" required />
                    <x-hearth-error for="contact_person_response_time" />
                </div>
            </fieldset>

            <p class="flex flex-wrap gap-7">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
            </p>
        </div>
    </div>
</form>
