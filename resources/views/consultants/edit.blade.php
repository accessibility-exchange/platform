
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('consultant.edit_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('consultants.update', $consultant) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <fieldset>
            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('consultant.label_name')" />
                <x-hearth-input type="text" name="name" :value="old('name', $consultant->name)" required hinted />
                <x-hearth-hint for="name">{{ __('consultant.hint_name') }}</x-hearth-hint>
                <x-hearth-error for="name" />
            </div>
        </fieldset>

        {{-- TODO: Add picture. --}}

        <fieldset>
            <div class="field @error('bio') field--error @enderror">
                <x-hearth-label for="bio" :value="__('consultant.label_bio')" />
                <x-hearth-textarea name="bio" hinted>{{ old('bio', $consultant->bio) }}</x-hearth-textarea>
                <x-hearth-hint for="bio">{{ __('consultant.hint_bio') }}</x-hearth-hint>
                <x-hearth-error for="bio" />
            </div>

            {{-- TODO: Upload a file. --}}
        </fieldset>

        <fieldset>
            <legend>{{ __('consultant.legend_address') }}</legend>

            <p class="field__hint" id="address-hint">{{ __('consultant.hint_address') }}</p>

            <div class="field @error('locality') field--error @enderror">
                <x-hearth-label for="locality" :value="__('forms.label_locality')" />
                <x-privacy-indicator id="locality-privacy" :value="__('consultant.privacy_matching_team')" />
                <x-hearth-input type="text" name="locality" value="{{ old('locality', $consultant->locality) }}" required hinted="address-hint locality-privacy" />
                <x-hearth-error for="locality" />
            </div>

            <div class="field @error('region') field--error @enderror">
                <x-hearth-label for="region" :value="__('forms.label_region')" />
                <x-hearth-select name="region" required :options="$regions" :selected="old('region', $consultant->region)" hinted="address-hint" />
                <x-hearth-error for="region" />
            </div>
        </fieldset>

        <x-hearth-date-input :label="__('consultant.label_birth_date')" name="birth_date" :value="old('birth_date', $consultant->birth_date)">
            <x-slot name="hint">
                <x-privacy-indicator :value="__('consultant.privacy_matching_team')" />
                {{ __('consultant.hint_birth_date') }}
            </x-slot>
        </x-hearth-date-input>

        <div class="field @error('pronouns') field--error @enderror">
            <x-hearth-label for="pronouns" :value="__('consultant.label_pronouns')" />
            <x-hearth-input type="text" name="pronouns" value="{{ old('pronouns', $consultant->pronouns) }}" hinted />
            <x-hearth-hint for="pronouns">{{ __('consultant.hint_pronouns') }}</x-hearth-hint>
            <x-hearth-error for="pronouns" />
        </div>

        <fieldset x-data="{ creator: '{{ old('creator') ?? 'self' }}' }">
            <legend>{{ __('consultant.legend_creator') }}</legend>
            <x-privacy-indicator id="creator-privacy" :value="__('consultant.privacy_matching_team')" />
            <x-hearth-radio-buttons name="creator" :options="$creators" :selected="old('creator', $consultant->creator)" x-model="creator" />
            <div class="field @error('creator_name') field--error @enderror" x-show="creator == 'other'">
                <x-hearth-label for="creator_name" :value="__('consultant.label_creator_name')" />
                <x-hearth-input type="text" name="creator_name" value="{{ old('creator_name', $consultant->creator_name) }}" hinted="creator-privacy" />
                <x-hearth-error for="creator_name" />
            </div>
            <div class="field @error('creator_relationship') field--error @enderror" x-show="creator == 'other'">
                <x-hearth-label for="creator_relationship" :value="__('consultant.label_creator_relationship')" />
                <x-hearth-input type="text" name="creator_relationship" value="{{ old('creator_relationship', $consultant->creator_relationship) }}" hinted="creator-privacy" />
                <x-hearth-error for="creator_relationship" />
            </div>
        </fieldset>

        <fieldset>
            <legend>{{ __('consultant.legend_visibility') }}</legend>
            <p class="field__hint">{{ __('consultant.hint_visibility') }}</p>
            <x-hearth-radio-buttons name="visibility" :options="$visibilities" :selected="old('visibility', 'all')" />
        </fieldset>

        <x-hearth-button>{{ __('forms.save_changes') }}</x-hearth-button>
    </form>

    <h2>
        {{ __('consultant.delete_title') }}
    </h2>

    <p>{{ __('consultant.delete_intro') }}</p>

    <form action="{{ localized_route('consultants.destroy', $consultant) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyConsultant') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="destroyConsultant" />
        </div>

        <x-hearth-button>
            {{ __('consultant.action_delete') }}
        </x-hearth-button>
    </form>
</x-app-layout>
