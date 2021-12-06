<fieldset>
    <div class="field @error('name') field--error @enderror">
        <x-hearth-label for="name" :value="__('Your name (required)')" />
        <x-hearth-input type="text" name="name" :value="old('name', $communityMember->name)" required hinted />
            <x-hearth-hint for="name">{{ __('This is the name that will be displayed on your page.') }}<br />{{ __('(This does not have to be your legal name.)') }}</x-hearth-hint>
        <x-hearth-error for="name" />
    </div>
</fieldset>

<fieldset>
    <legend>{{ __('Who’s creating this community member page? (required)') }}</legend>
    <x-hearth-radio-buttons name="creator" :options="$creators" :selected="old('creator', $communityMember->creator)" required />
</fieldset>

<fieldset>
    <legend>{{ __('Where do you live? (required)') }}</legend>

    <p class="field__hint" id="address-hint">{{ __('This will help us match you to projects in your location.') }}</p>

    <div class="field @error('locality') field--error @enderror">
        <x-hearth-label for="locality" :value="__('forms.label_locality')" />
        <x-hearth-input type="text" name="locality" value="{{ old('locality', $communityMember->locality) }}" required hinted="address-hint locality-privacy" />
        <x-hearth-error for="locality" />
    </div>

    <div class="field @error('region') field--error @enderror">
        <x-hearth-label for="region" :value="__('forms.label_region')" />
        <x-hearth-select name="region" required :options="$regions" :selected="old('region', $communityMember->region)" hinted="address-hint" />
        <x-hearth-error for="region" />
    </div>
</fieldset>

<div class="field @error('pronouns') field--error @enderror">
    <x-hearth-label for="pronouns" :value="__('What are your pronouns? (optional)')" />
    <x-hearth-hint for="pronouns">{{ __('For example: he/him, she/her, they/them.') }}</x-hearth-hint>
    <x-hearth-input type="text" name="pronouns" value="{{ old('pronouns', $communityMember->pronouns) }}" hinted />
    <x-hearth-error for="pronouns" />
</div>

<fieldset>
    <div class="field @error('bio') field--error @enderror">
        <x-hearth-label for="bio" :value="__('Your bio (optional)')" />
        <x-hearth-hint for="bio">{{ __('Tell everyone a bit more about yourself.') }}</x-hearth-hint>
        <x-hearth-textarea name="bio" hinted>{{ old('bio', $communityMember->bio) }}</x-hearth-textarea>
        <x-hearth-error for="bio" />
    </div>

    {{-- TODO: Upload a file. --}}
</fieldset>

<fieldset>
    <legend>{{ __('Social media and links') }}</legend>
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

    <div class="field @error('links.other') field--error @enderror">
        <x-hearth-label for="links_other" :value="__('Other website link')" />
        <x-hearth-input id="links_other" name="links[other]" :value="old('links[other]', $communityMember->links['other'] ?? '')" hinted />
        <x-hearth-hint for="links_other">{{ __('For example, your personal website, portfolio, or blog.') }}</x-hearth-hint>
    <x-hearth-error for="links_other" />
    </div>
</fieldset>
