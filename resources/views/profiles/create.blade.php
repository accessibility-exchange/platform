
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('profile.create_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('profiles.store') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />

        <fieldset>
            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('profile.label_name')" />
                <x-hearth-input id="name" type="text" name="name" :value="Auth::user()->name" required aria-describedby="name-hint" />
                <p class="field--hint" id="name-hint">{{ __('profile.hint_name') }}</p>
                <x-field-error for="name" />
            </div>
        </fieldset>

        <fieldset>{{-- TODO: Add picture. --}}</fieldset>

        <fieldset>
            <div class="field @error('bio') field--error @enderror">
                <x-hearth-label for="bio" :value="__('profile.label_bio')" />
                <textarea id="bio" name="bio" aria-describedby="bio-hint">{{ old('bio') }}</textarea>
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
                <x-hearth-input id="locality" type="text" name="locality" value="{{ old('locality') }}" required aria-describedby="address-hint" />
                <x-field-error for="locality" />
            </div>

            <div class="field @error('region') field--error @enderror">
                <x-hearth-label for="region" :value="__('forms.label_region')" />
                <x-hearth-select id="region" name="region" required :options="$regions" :selected="old('region')" aria-describedby="address-hint" />
                <x-field-error for="region" />
            </div>
        </fieldset>

        <x-date-input :label="__('profile.label_birth_date')" name="birth_date" :value="old('birth_date', '')" />

        <fieldset x-data="{ other: false }">
            <legend>{{ __('profile.legend_pronouns') }}</legend>
            <p class="field--hint" id="pronouns-hint">{{ __('profile.hint_pronouns') }}</p>

            <div>
                <input type="checkbox" id="pronouns-masculine" name="pronouns" value="He/him/his" @if(old('pronouns') === 'He/him/his') checked @endif />
                <x-hearth-label for="pronouns-masculine" :value="__('He/him/his')" />
            </div>
            <div>
                <input type="checkbox" id="pronouns-feminine" name="pronouns" value="She/her/hers" @if(old('pronouns') === 'She/her/hers') checked @endif />
                <x-hearth-label for="pronouns-feminine" :value="__('She/her/hers')" />
            </div>
            <div>
                <input type="checkbox" id="pronouns-neutral" name="pronouns" value="They/them/theirs" @if(old('pronouns') === 'They/them/theirs') checked @endif />
                <x-hearth-label for="pronouns-neutral" :value="__('They/them/theirs')" />
            </div>
            <div>
                <input type="checkbox" id="pronouns-other" name="pronouns" value="other" x-model="other" @if(old('pronouns') === 'other') checked @endif />
                <x-hearth-label id="other-pronouns-label" for="pronouns-other" :value="__('Other pronouns')" />
                <x-hearth-input id="other-pronouns" type="text" name="other-pronouns" value="{{ old('other-pronouns') }}" aria-labelledby="other-pronouns-label" x-show="other" />
            </div>
        </fieldset>

        <fieldset x-data="{ creator: '{{ old('creator') ?? 'self' }}' }">
            <legend>{{ __('profile.legend_creator') }}</legend>
            <div>
                <input type="radio" id="creator-self" name="creator" value="self" x-model="creator" @if(old('creator') == 'self') checked @endif />
                <x-hearth-label for="creator-self" :value="__('profile.label_creator_self')" />
            </div>
            <div>
                <input type="radio" id="creator-other" name="creator" value="other" x-model="creator" @if(old('creator') == 'other') checked @endif />
                <x-hearth-label for="creator-other" :value="__('profile.label_creator_other')" />
                <div @error('creator_name')class="field--error"@enderror x-show="creator == 'other'">
                    <x-hearth-label for="creator_name" :value="__('profile.label_creator_name')" />
                    <x-hearth-input id="creator_name" type="text" name="creator_name" value="{{ old('creator_name') }}" />
                    <x-field-error for="creator_name" />
                </div>
                <div @error('creator_relationship')class="field--error"@enderror x-show="creator == 'other'">
                    <x-hearth-label for="creator_relationship" :value="__('profile.label_creator_relationship')" />
                    <x-hearth-input id="creator_relationship" type="text" name="creator_relationship" value="{{ old('creator_relationship') }}" />
                    <x-field-error for="creator_relationship" />
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>{{ __('profile.legend_visibility') }}</legend>
            <p class="field--hint">{{ __('profile.hint_visibility') }}</p>
            <div>
                <input type="radio" id="visibility-all" name="visibility" value="all" @if(old('visibility') !== 'project') checked @endif />
                <x-hearth-label for="visibility-all" :value="__('profile.label_visibility_all')" />
            </div>
            <div>
                <input type="radio" id="visibility-projects" name="visibility" value="project" @if(old('visibility') == 'project') checked @endif />
                <x-hearth-label for="visibility-projects" :value="__('profile.label_visibility_project')" />
            </div>
        </fieldset>

        <input type="submit" name="save_draft" value="{{ __('profile.action_save_draft') }}" />
        <input type="submit" name="publish" value="{{ __('profile.action_save_and_publish') }}" />
    </form>

    {{-- <x-slot name="aside">
        <h2>{{ __('Need some support?') }}</h2>
        <ul role="list">
            <li><a href="#">{{ __('Call the support line') }}</a></li>
            <li><a href="#">{{ __('E-mail us') }}</a></li>
        </ul>
    </x-slot> --}}
</x-app-layout>
