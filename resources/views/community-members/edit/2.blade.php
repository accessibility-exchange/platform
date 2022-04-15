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
        <legend>{{ __('What types of federally regulated organization are you interested in?') }}</legend>
        <x-hearth-checkboxes name="sectors" :options="$sectors" :checked="old('sectors', $communityMember->sectors->pluck('id')->toArray())" />
        <x-hearth-error for="sectors" />
    </fieldset>

    <fieldset class="field @error('impacts') field--error @enderror">
        <legend>{{ __('What areas would you most like to impact within a federally regulated organization?') }}</legend>
        <x-hearth-hint for="impacts">{{ __('These are the seven areas listed within the Accessible Canada Act. By law, entities must ensure these areas are accessible.') }}</x-hearth-hint>
        <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $communityMember->impacts->pluck('id')->toArray())" />
        <x-hearth-error for="impacts" />
    </fieldset>

    <p>
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
