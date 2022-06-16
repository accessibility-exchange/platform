<form class="stack" action="{{ localized_route('organizations.update-interests', $organization) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                {{ __('Interests') }}
            </h2>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>

            <p>{{ __('This information is used to tell regulated organizations  if you have any special interests. This entire page is optional.') }}</p>

            <fieldset class="field @error('sectors') field--error @enderror">
                <legend>{{ __('What sectors of Federally Regulated Organizations are you interested in working with? (optional)') }}</legend>
                <x-hearth-checkboxes name="sectors" :options="$sectors" :checked="old('sectors', $organization->sectors->pluck('id')->toArray())" />
                <x-hearth-error for="sectors" />
            </fieldset>

            <fieldset class="field @error('impacts') field--error @enderror">
                <legend>{{ __('What areas of the Accessible Canada Act are you most interested in working on? (optional)') }}</legend>
                <x-hearth-hint for="impacts">{{ __('These are the seven areas listed within the Accessible Canada Act. Federally regulated organizations must work to improve their accessibility in all of these areas.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $organization->impacts->pluck('id')->toArray())" />
                <x-hearth-error for="impacts" />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>
