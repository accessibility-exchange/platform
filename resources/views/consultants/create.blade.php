
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('consultant.create_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('consultants.store') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />

        <fieldset>
            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('consultant.label_name')" />
                <x-hearth-hint for="name">{{ __('consultant.hint_name') }}</x-hearth-hint>
                <x-hearth-input type="text" name="name" :value="Auth::user()->name" required hinted />
                <x-hearth-error for="name" />
            </div>
        </fieldset>

        {{-- TODO: Add picture. --}}

        <fieldset>
            <div class="field @error('bio') field--error @enderror">
                <x-hearth-label for="bio" :value="__('consultant.label_bio')" />
                <x-hearth-hint for="bio">{{ __('consultant.hint_bio') }}</x-hearth-hint>
                <x-hearth-textarea name="bio" hinted>{{ old('bio') }}</x-hearth-textarea>
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
                <x-hearth-input type="text" name="locality" value="{{ old('locality') }}" required hinted="address-hint locality-privacy" />
                <x-hearth-error for="locality" />
            </div>

            <div class="field @error('region') field--error @enderror">
                <x-hearth-label for="region" :value="__('forms.label_region')" />
                <x-hearth-select name="region" required :options="$regions" :selected="old('region')" hinted="address-hint" />
                <x-hearth-error for="region" />
            </div>
        </fieldset>

        <x-hearth-date-input :label="__('consultant.label_birth_date')" name="birth_date" :value="old('birth_date', '')">
            <x-slot name="hint">
                <x-privacy-indicator :value="__('consultant.privacy_matching_team')" />
                {{ __('consultant.hint_birth_date') }}
            </x-slot>
        </x-hearth-date-input>

        <div class="field @error('pronouns') field--error @enderror">
            <x-hearth-label for="pronouns" :value="__('consultant.label_pronouns')" />
            <x-hearth-hint for="pronouns">{{ __('consultant.hint_pronouns') }}</x-hearth-hint>
            <x-hearth-input type="text" name="pronouns" value="{{ old('pronouns') }}" hinted />
            <x-hearth-error for="pronouns" />
        </div>

        <fieldset x-data="{ creator: '{{ old('creator') ?? 'self' }}' }">
            <legend>{{ __('consultant.legend_creator') }}</legend>
            <x-privacy-indicator id="creator-privacy" :value="__('consultant.privacy_matching_team')" />
            <x-hearth-radio-buttons name="creator" :options="$creators" :selected="old('creator', 'self')" x-model="creator" />
            <div class="field @error('creator_name') field--error @enderror" x-show="creator == 'other'">
                <x-hearth-label for="creator_name" :value="__('consultant.label_creator_name')" />
                <x-hearth-input type="text" name="creator_name" value="{{ old('creator_name') }}" hinted="creator-privacy" />
                <x-hearth-error for="creator_name" />
            </div>
            <div class="field @error('creator_relationship') field--error @enderror" x-show="creator == 'other'">
                <x-hearth-label for="creator_relationship" :value="__('consultant.label_creator_relationship')" />
                <x-hearth-input type="text" name="creator_relationship" value="{{ old('creator_relationship') }}" hinted="creator-privacy" />
                <x-hearth-error for="creator_relationship" />
            </div>
        </fieldset>

        <fieldset>
            <legend>{{ __('consultant.legend_visibility') }}</legend>
            <p class="field__hint">{{ __('consultant.hint_visibility') }}</p>
            <x-hearth-radio-buttons name="visibility" :options="$visibilities" :selected="old('visibility', 'all')" />
        </fieldset>

        <x-hearth-button>{{ __('consultant.action_save_draft') }}</x-hearth-button>
    </form>
</x-app-layout>
