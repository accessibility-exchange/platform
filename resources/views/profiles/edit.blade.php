
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
                <x-hearth-input type="text" name="name" :value="old('name', $profile->name)" required hinted />
                <x-hearth-hint for="name">{{ __('profile.hint_name') }}</x-hearth-hint>
                <x-hearth-error for="name" />
            </div>
        </fieldset>

        {{-- TODO: Add picture. --}}

        <fieldset>
            <div class="field @error('bio') field--error @enderror">
                <x-hearth-label for="bio" :value="__('profile.label_bio')" />
                <x-hearth-textarea name="bio" hinted>{{ old('bio', $profile->bio) }}</x-hearth-textarea>
                <x-hearth-hint for="bio">{{ __('profile.hint_bio') }}</x-hearth-hint>
                <x-hearth-error for="bio" />
            </div>

            {{-- TODO: Upload a file. --}}
        </fieldset>

        <fieldset>
            <legend>{{ __('profile.legend_address') }}</legend>

            <p class="field__hint" id="address-hint">{{ __('profile.hint_address') }}</p>

            <div class="field @error('locality') field--error @enderror">
                <x-hearth-label for="locality" :value="__('forms.label_locality')" />
                <x-hearth-input type="text" name="locality" value="{{ old('locality', $profile->locality) }}" required hinted="address-hint" />
                <x-hearth-error for="locality" />
            </div>

            <div class="field @error('region') field--error @enderror">
                <x-hearth-label for="region" :value="__('forms.label_region')" />
                <x-hearth-select name="region" required :options="$regions" :selected="old('region', $profile->region)" hinted="address-hint" />
                <x-hearth-error for="region" />
            </div>
        </fieldset>

        <x-hearth-date-input :label="__('profile.label_birth_date')" name="birth_date" :hint="__('profile.hint_birth_date')" :value="old('birth_date', $profile->birth_date)" />

        <div class="field @error('pronouns') field--error @enderror">
            <x-hearth-label for="pronouns" :value="__('profile.label_pronouns')" />
            <x-hearth-input type="text" name="pronouns" value="{{ old('pronouns', $profile->pronouns) }}" hinted />
            <x-hearth-hint for="pronouns">{{ __('profile.hint_pronouns') }}</x-hearth-hint>
            <x-hearth-error for="pronouns" />
        </div>

        {{-- TODO: Use radio-buttons component --}}
        <fieldset x-data="{ creator: '{{ old('creator') ?? 'self' }}' }">
            <legend>{{ __('profile.legend_creator') }}</legend>
            <div>
                <input type="radio" id="creator-self" name="creator" value="self" x-model="creator" @if(old('creator', $profile->creator) == 'self') checked @endif />
                <x-hearth-label for="creator-self" :value="__('profile.label_creator_self')" />
            </div>
            <div>
                <input type="radio" id="creator-other" name="creator" value="other" x-model="creator" @if(old('creator', $profile->creator) == 'other') checked @endif />
                <x-hearth-label for="creator-other" :value="__('profile.label_creator_other')" />
            </div>
            <div class="field @error('creator_name') field--error @enderror" x-show="creator == 'other'">
                <x-hearth-label for="creator_name" :value="__('profile.label_creator_name')" />
                <x-hearth-input type="text" name="creator_name" value="{{ old('creator_name', $profile->creator_name) }}" />
                <x-hearth-error for="creator_name" />
            </div>
            <div class="field @error('creator_relationship') field--error @enderror" x-show="creator == 'other'">
                <x-hearth-label for="creator_relationship" :value="__('profile.label_creator_relationship')" />
                <x-hearth-input type="text" name="creator_relationship" value="{{ old('creator_relationship', $profile->creator_relationship) }}" />
                <x-hearth-error for="creator_relationship" />
            </div>
        </fieldset>

        {{-- TODO: Use radio-buttons component --}}
        <fieldset>
            <legend>{{ __('profile.legend_visibility') }}</legend>
            <p class="field__hint">{{ __('profile.hint_visibility') }}</p>
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

        <div class="field @error('current_password', 'destroyProfile') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="destroyProfile" />
        </div>

        <x-hearth-button>
            {{ __('profile.action_delete') }}
        </x-hearth-button>
    </form>
</x-app-layout>
