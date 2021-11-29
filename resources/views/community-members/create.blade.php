
<x-app-layout>
    <x-slot name="title">{{ __('Create your community member page') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create your community member page') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('community-members.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <x-privacy-indicator level="public" :value="__('This information will be on your public page. It is visible to anyone with an account on this website.')" />

        <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />

        <fieldset>
            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('Your name')" />
                <x-hearth-hint for="name">{{ __('This is the name that will be displayed on your page.') }}</x-hearth-hint>
                <x-hearth-input type="text" name="name" :value="Auth::user()->name" required hinted />
                <x-hearth-error for="name" />
            </div>
        </fieldset>

        <fieldset>
            <div class="field @error('picture') field--error @enderror">
                <x-hearth-label for="picture" :value="__('Your picture (optional)')" />
                <x-hearth-hint for="picture">{{ __('This will be the picture that others use to identify you.') }}</x-hearth-hint>
                @livewire('image-uploader', ['name' => 'picture', 'image' => null, 'alt' => false])
                <x-hearth-error for="picture" />
            </div>
        </fieldset>

        <fieldset>
            <div class="field @error('picture_alt') field--error @enderror">
                <x-hearth-label for="picture_alt" :value="__('Alternative text for your picture')" />
                <x-hearth-input name="picture_alt" :value="old('picture_alt')" />
                <x-hearth-error for="picture_alt" />
            </div>
        </fieldset>

        <fieldset>
            <div class="field @error('bio') field--error @enderror">
                <x-hearth-label for="bio" :value="__('Your bio')" />
                <x-hearth-hint for="bio">{{ __('Tell everyone a bit more about yourself.') }}</x-hearth-hint>
                <x-hearth-textarea name="bio" hinted>{{ old('bio') }}</x-hearth-textarea>
                <x-hearth-error for="bio" />
            </div>

            {{-- TODO: Upload a file. --}}
        </fieldset>

        <fieldset>
            <legend>{{ __('Link') }}</legend>
            <x-hearth-hint for="links">{{ __('You can add a link to content that you want to share with others about you.') }}</x-hearth-hint>
                @for ($i = 0; $i < 1; $i++)
                <div class="field @error('links.' . $i . '.url') field--error @enderror">
                    <x-hearth-label for="links_{{ $i }}_url" :value="__('Link address')" />
                    <x-hearth-input id="links_{{ $i }}_url" name="links[{{ $i }}][url]" :value="old('links[' . $i . '][url]', '')" hinted="links-hint" />
                <x-hearth-error for="links_{{ $i }}_url" />
                </div>
                <div class="field @error('links.' . $i . '.text') field--error @enderror">
                    <x-hearth-label for="links_{{ $i }}_text" :value="__('Link text')" />
                    <x-hearth-input id="links_{{ $i }}_text" name="links[{{ $i }}][text]" :value="old('links[' . $i . '][text]', '')" hinted="links-hint" />
                    <x-hearth-error for="links_{{ $i }}_text" />
                </div>
            @endfor

        </fieldset>

        <fieldset>
            <legend>{{ __('Where do you live?') }}</legend>

            <p class="field__hint" id="address-hint">{{ __('This will help us match you to a project in your location.') }}</p>

            <div class="field @error('locality') field--error @enderror">
                <x-hearth-label for="locality" :value="__('forms.label_locality')" />
                <x-hearth-input type="text" name="locality" value="{{ old('locality') }}" required hinted="address-hint locality-privacy" />
                <x-hearth-error for="locality" />
            </div>

            <div class="field @error('region') field--error @enderror">
                <x-hearth-label for="region" :value="__('forms.label_region')" />
                <x-hearth-select name="region" required :options="$regions" :selected="old('region')" hinted="address-hint" />
                <x-hearth-error for="region" />
            </div>
        </fieldset>

        <div class="field @error('pronouns') field--error @enderror">
            <x-hearth-label for="pronouns" :value="__('What are your pronouns? (optional)')" />
            <x-hearth-hint for="pronouns">{{ __('This will help others use the correct pronouns for you (e.g. he/him, she/her, they/them).') }}</x-hearth-hint>
            <x-hearth-input type="text" name="pronouns" value="{{ old('pronouns') }}" hinted />
            <x-hearth-error for="pronouns" />
        </div>

        <fieldset x-data="{ creator: '{{ old('creator') ?? 'self' }}' }">
            <legend>{{ __('Who’s creating this community member page?') }}</legend>
            <x-hearth-radio-buttons name="creator" :options="$creators" :selected="old('creator', 'self')" x-model="creator" />
            <div class="field @error('creator_name') field--error @enderror" x-show="creator == 'other'">
                <x-hearth-label for="creator_name" :value="__('Name')" />
                <x-hearth-input type="text" name="creator_name" value="{{ old('creator_name') }}" />
                <x-hearth-error for="creator_name" />
            </div>
            <div class="field @error('creator_relationship') field--error @enderror" x-show="creator == 'other'">
                <x-hearth-label for="creator_relationship" :value="__('Relationship to community member')" />
                <x-hearth-input type="text" name="creator_relationship" value="{{ old('creator_relationship') }}" />
                <x-hearth-error for="creator_relationship" />
            </div>
        </fieldset>

        <x-hearth-button>{{ __('Save draft') }}</x-hearth-button>
    </form>
</x-app-layout>
