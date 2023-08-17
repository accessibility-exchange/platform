<form class="stack" action="{{ localized_route('organizations.update-interests', $organization) }}" method="POST"
    enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                {{ __('Interests') }}
            </h2>
            <x-interpretation name="{{ __('Interests', [], 'en') }}" />
            <hr class="divider--thick">
            <p>{{ __('This information is used to tell regulated organizations if you have any special interests. This entire page is optional.') }}
            </p>

            <fieldset class="field @error('sectors') field--error @enderror">
                <legend>
                    {{ __('What types of Regulated Organization are you interested consulting with on accessibility planning and design?') . ' ' . __('(optional)') }}
                </legend>
                <x-interpretation class="interpretation--start"
                    name="{{ __('What types of Regulated Organization are you interested consulting with on accessibility planning and design?', [], 'en') }}"
                    namespace="regulated_organization_sectors_you_are_interested_working-optional" />
                <x-hearth-checkboxes name="sectors" :options="$sectors" :checked="old('sectors', $organization->sectors->pluck('id')->toArray())" />
                <x-hearth-error for="sectors" />
            </fieldset>

            <fieldset class="field @error('impacts') field--error @enderror">
                <legend>
                    {{ __('What areas of accessibility planning and design are you most interested in consulting on?') . ' ' . __('(optional)') }}
                </legend>
                <x-interpretation class="interpretation--start"
                    name="{{ __('What areas of accessibility planning and design are you most interested in consulting on?', [], 'en') }}"
                    namespace="areas_of_accessible_canada_act_you_are_interested_working-optional" />
                <x-hearth-hint for="impacts">
                    {{ __('These are the seven areas listed within the Accessible Canada Act. Federally regulated organizations must work to improve their accessibility in all of these areas.') }}
                </x-hearth-hint>
                <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $organization->impacts->pluck('id')->toArray())" />
                <x-hearth-error for="impacts" />
            </fieldset>
            <hr class="divider--thick">
            <x-interpretation
                name="{{ __('Save and back', [], 'en') . _ . __('Save', [], 'en') . _ . __('Save and next', [], 'en') }}"
                namespace="save_and_back_save_save_and_next" />
            <p class="flex flex-wrap gap-7">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button class="secondary" name="save" value="1">{{ __('Save') }}</button>
                <button name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>
