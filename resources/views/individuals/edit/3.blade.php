<form action="{{ localized_route('individuals.update-interests', $individual) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">
        @include('individuals.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step'), 'total' => 4]) }}<br />
                {{ __('Interests') }}
            </h2>

            <p class="repel">
                <button class="secondary" name="save_and_previous">{{ __('Save and previous') }}</button>
                <button name="save">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next">{{ __('Save and next') }}</button>
            </p>

            <fieldset class="field @error('sectors') field--error @enderror">
                <legend>{{ __('What sectors of Federally Regulated Organizations are you interested in working with? (optional)') }}</legend>
                <x-hearth-checkboxes name="sectors" :options="$sectors" :checked="old('sectors', $individual->sectors->pluck('id')->toArray())" />
                <x-hearth-error for="sectors" />
            </fieldset>

            <fieldset class="field @error('impacts') field--error @enderror">
                <legend>{{ __('What areas of the Accessible Canada Act are you most interested in working on? (optional)') }}</legend>
                <x-hearth-hint for="impacts">{{ __('These are the seven areas listed within the Accessible Canada Act. Federally regulated organizations must work to improve their accessibility in all of these areas.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $individual->impacts->pluck('id')->toArray())" />
                <x-hearth-error for="impacts" />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="save_and_previous">{{ __('Save and previous') }}</button>
                <button name="save">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>
