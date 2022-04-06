<form action="{{ localized_route('community-members.update-interests', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <h2>
        {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 5]) }}<br />
        {{ __('Interests') }}
    </h2>

    @include('community-members.partials.progress')

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>

    <p>{{ __('This information is used to tell entities if you have any special interests. This entire page is optional.') }}</p>

    <x-privacy-indicator level="public" :value="__('This information will be on your public page. It is visible to anyone with an account on this website.')" />

    <fieldset class="field @error('sectors') field--error @enderror">
        <legend>{{ __('What types of federally regulated organization are you interested in? (optional)') }}</legend>
        <x-hearth-checkboxes name="sectors" :options="$sectors" :checked="old('sectors', $communityMember->sectors->pluck('id')->toArray())" />
        <x-hearth-error for="sectors" />
    </fieldset>

    <fieldset class="field @error('impacts') field--error @enderror">
        <legend>{{ __('What areas would you most like to impact within a federally regulated organization? (optional)') }}</legend>
        <x-hearth-hint for="impacts">{{ __('These are the seven areas listed within the Accessible Canada Act. By law, entities must ensure these areas are accessible.') }}</x-hearth-hint>
        <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $communityMember->impacts->pluck('id')->toArray())" />
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

    <fieldset>
        <legend>{{ __('Service preference (optional)') }}</legend>
        <x-hearth-hint for="service_preference">{{ __('Which type of services do you want to consult on? Check all that apply.') }}</x-hearth-hint>
        <x-hearth-checkboxes name="service_preference" :options="$servicePreferences" :checked="old('service_preference', $communityMember->service_preference ?? [])" />
        <x-hearth-error for="service_preference" />
    </fieldset>

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
