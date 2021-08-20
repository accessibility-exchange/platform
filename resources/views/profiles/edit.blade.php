
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('profile.edit_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('profiles.update', $profile) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <fieldset>
            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('profile.label_name')" />
                <x-hearth-input id="name" type="text" name="name" :value="old('name', $profile->name)" required aria-describedby="name-hint" />
                <p class="field--hint" id="name-hint">{{ __('profile.hint_name') }}</p>
                <x-field-error for="name" />
            </div>
        </fieldset>

        <fieldset>{{-- TODO: Add picture. --}}</fieldset>

        <fieldset>
            <div class="field @error('bio') field--error @enderror">
                <x-hearth-label for="bio" :value="__('profile.label_bio')" />
                <textarea id="bio" name="bio" aria-describedby="bio-hint">{{ old('bio', $profile->bio) }}</textarea>
                <p class="field--hint" id="bio-hint">{{ __('profile.hint_bio') }}</p>
                <x-field-error for="bio" />
            </div>

            {{-- TODO: Upload a file. --}}
        </fieldset>

        <fieldset>
            <legend>{{ __('profile.legend_address') }}</legend>

            <p class="field--hint" id="address-hint">{{ __('profile.hint_address') }}</p>

            <div class="field @error('locality') field--error @enderror">
                <x-hearth-label for="locality" :value="__('forms.label_locality')" />
                <x-hearth-input id="locality" type="text" name="locality" value="{{ old('locality', $profile->locality) }}" required aria-describedby="address-hint" />
                <x-field-error for="locality" />
            </div>

            <div class="field @error('region') field--error @enderror">
                <x-hearth-label for="region" :value="__('forms.label_region')" />
                <x-hearth-select id="region" name="region" required :options="$regions" :selected="old('region', $profile->region)" aria-describedby="address-hint" />
                <x-field-error for="region" />
            </div>
        </fieldset>

        <x-date-input :label="__('profile.label_birth_date')" name="birth_date" :hint="__('profile.hint_birth_date')" :value="old('birth_date', $profile->birth_date)" />

        <fieldset x-data="{ other: false }">
            <div class="field @error('pronouns') field--error @enderror">
                <x-hearth-label for="pronouns" :value="__('profile.label_pronouns')" />
                <x-hearth-input id="pronouns" type="text" name="pronouns" value="{{ old('pronouns', $profile->pronouns) }}" />
                <p class="field--hint" id="pronouns-hint">{{ __('profile.hint_pronouns') }}</p>
                <x-field-error for="pronouns" />
            </div>
        </fieldset>

        <fieldset x-data="{ creator: '{{ old('creator') ?? 'self' }}' }">
            <legend>{{ __('profile.legend_creator') }}</legend>
            <div>
                <input type="radio" id="creator-self" name="creator" value="self" x-model="creator" @if(old('creator', $profile->creator) == 'self') checked @endif />
                <x-hearth-label for="creator-self" :value="__('profile.label_creator_self')" />
            </div>
            <div>
                <input type="radio" id="creator-other" name="creator" value="other" x-model="creator" @if(old('creator', $profile->creator) == 'other') checked @endif />
                <x-hearth-label for="creator-other" :value="__('profile.label_creator_other')" />
                <div @error('creator_name')class="field--error"@enderror x-show="creator == 'other'">
                    <x-hearth-label for="creator_name" :value="__('profile.label_creator_name')" />
                    <x-hearth-input id="creator_name" type="text" name="creator_name" value="{{ old('creator_name', $profile->creator_name) }}" />
                    <x-field-error for="creator_name" />
                </div>
                <div @error('creator_relationship')class="field--error"@enderror x-show="creator == 'other'">
                    <x-hearth-label for="creator_relationship" :value="__('profile.label_creator_relationship')" />
                    <x-hearth-input id="creator_relationship" type="text" name="creator_relationship" value="{{ old('creator_relationship', $profile->creator_relationship) }}" />
                    <x-field-error for="creator_relationship" />
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>{{ __('profile.legend_visibility') }}</legend>
            <p class="field--hint">{{ __('profile.hint_visibility') }}</p>
            <div>
                <input type="radio" id="visibility-all" name="visibility" value="all" @if(old('visibility', $profile->visibility) !== 'project') checked @endif />
                <x-hearth-label for="visibility-all" :value="__('profile.label_visibility_all')" />
            </div>
            <div>
                <input type="radio" id="visibility-projects" name="visibility" value="project" @if(old('visibility', $profile->visibility) == 'project') checked @endif />
                <x-hearth-label for="visibility-projects" :value="__('profile.label_visibility_project')" />
            </div>
        </fieldset>

        <x-hearth-button>{{ __('forms.save_changes') }}</x-hearth-button>
    </form>

    <h2>
        {{ __('profile.delete_title') }}
    </h2>

    <p>{{ __('profile.delete_intro') }}</p>

    <form action="{{ localized_route('profiles.destroy', $profile) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input id="current_password" type="password" name="current_password" required />
            @error('current_password', 'destroyProfile')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <x-hearth-button>
            {{ __('profile.action_delete') }}
        </x-hearth-button>
    </form>
</x-app-layout>
