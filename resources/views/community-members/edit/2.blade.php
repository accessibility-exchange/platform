<form action="{{ localized_route('community-members.update-interests', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <x-privacy-indicator level="public" :value="__('This information will be on your public page. It is visible to anyone with an account on this website.')" />

    <fieldset class="field @error('sectors') field--error @enderror">
        <legend>{{ __('What types of regulated entity are you interested in?') }}</legend>
        <x-hearth-checkboxes name="sectors" :options="$sectors" :selected="old('sectors', $communityMember->sectors->pluck('id')->toArray())" />
        <x-hearth-error for="sectors" />
    </fieldset>

    <fieldset class="field @error('impacts') field--error @enderror">
        <legend>{{ __('What areas would you most like to impact within a regulated entity?') }}</legend>
        <x-hearth-hint for="impacts">{{ __('These are the seven areas listed within the Accessible Canada Act. By law, entities must ensure these areas are accessible.') }}</x-hearth-hint>
        <x-hearth-checkboxes name="impacts" :options="$impacts" :selected="old('impacts', $communityMember->impacts->pluck('id')->toArray())" />
        <x-hearth-error for="impacts" />
    </fieldset>

    <fieldset>
        <div class="field @error('areas_of_interest') field--error @enderror">
            <x-hearth-label for="areas_of_interest" :value="__('Areas of interest (optional)')" />
            <x-hearth-hint for="areas_of_interest">{{ __('Are there other areas that you’re interested in that wasn’t listed in the previous question?') }}</x-hearth-hint>
            <x-hearth-textarea name="areas_of_interest" hinted>{{ old('areas_of_interest', $communityMember->areas_of_interest) }}</x-hearth-textarea>
            <x-hearth-error for="areas_of_interest" />
        </div>
    </fieldset>

    <x-hearth-button>{{ __('Save changes') }}</x-hearth-button>
</form>
