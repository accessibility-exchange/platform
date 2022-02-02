
<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 5]) }}<br />
    {{ __('About you') }}
</h2>

@include('community-members.partials.progress')

<form action="{{ localized_route('community-members.update', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <x-privacy-indicator level="public" :value="__('Any member of the website can find this information.')" />

    <fieldset>
        <div class="field @error('name') field--error @enderror">
            <x-hearth-label for="name" :value="__('Display name (required)')" />
            <x-hearth-input type="text" name="name" :value="old('name', $communityMember->name)" required hinted />
                <x-hearth-hint for="name">{{ __('This is the name that will be displayed on your page. This does not have to be your legal name.') }}</x-hearth-hint>
            <x-hearth-error for="name" />
        </div>
    </fieldset>

    <fieldset>
        <legend>{{ __('Where do you live?') }}</legend>

        <p class="field__hint" id="address-hint">{{ __('Your location will be used if entities are looking to get feedback on their services in a certain place.') }}</p>

        <div class="field @error('region') field--error @enderror">
            <x-hearth-label for="region" :value="__('Province or territory (required)')" />
            <x-hearth-select name="region" required :options="$regions" :selected="old('region', $communityMember->region)" hinted="address-hint" />
            <x-hearth-error for="region" />
        </div>

        <div class="field @error('locality') field--error @enderror">
            <x-hearth-label for="locality" :value="__('City or town (optional)')" />
            <x-hearth-input type="text" name="locality" value="{{ old('locality', $communityMember->locality) }}" hinted="address-hint locality-privacy" />
            <x-hearth-error for="locality" />
        </div>

        <div class="field">
            <x-hearth-checkbox name="hide_location" :checked="old('hide_location', $communityMember->hide_location ?? false)" />
            <x-hearth-label for="hide_location" :value="__('Hide my location from anyone besides entities looking to hire me')" />
        </div>

        <x-info :value="__('If you hide your location, you will not show up on search results based on location.')" />

    </fieldset>

    <div class="field @error('pronouns') field--error @enderror">
        <x-hearth-label for="pronouns" :value="__('Pronouns (optional)')" />
        <x-hearth-hint for="pronouns">{{ __('For example: he/him, she/her, they/them.') }}</x-hearth-hint>
        <x-hearth-input type="text" name="pronouns" value="{{ old('pronouns', $communityMember->pronouns) }}" hinted />
        <x-hearth-error for="pronouns" />
    </div>

    <fieldset>
        <div class="field @error('bio') field--error @enderror">
            <x-hearth-label for="bio" :value="__('Your bio (optional)')" />
            {{-- <p><a href="#">{{ __('Show an example') }}</a></p> --}}
            <x-hearth-hint for="bio">{{ __('This can include information about your background, and why you are interested in accessibility.') }}</x-hearth-hint>
            <x-hearth-textarea name="bio" hinted>{{ old('bio', $communityMember->bio) }}</x-hearth-textarea>
            <x-hearth-error for="bio" />
        </div>

        {{-- TODO: Upload a file. --}}
    </fieldset>

    <h3>{{ __('Social media and website links (optional)') }}</h3>

    <fieldset>
        <legend>{{ __('Social media (optional)') }}</legend>

        @foreach ([
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'facebook' => 'Facebook'
        ] as $key => $label)
            <div class="field @error('links.' . $key) field--error @enderror">
                <x-hearth-label for="links_{{ $key }}" :value="__(':service link', ['service' => $label] )" />
                <x-hearth-input id="links_{{ $key }}" name="links[{{ $key }}]" :value="old('links[' . $key . ']', $communityMember->links[$key] ?? '')" />
                <x-hearth-error for="links_{{ $key }}" />
            </div>
        @endforeach
    </fieldset>

    <fieldset class="flow">
        <legend>{{ __('Other websites (optional)') }}</legend>
        <p class="field__hint">{{ __('This could be your personal website, a blog or portfolio, or articles about your work.') }}</p>
        <livewire:other-links :links="$communityMember->other_links ?? [['title' => '', 'url' => '']]" />
    </fieldset>

    <p>
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
