<form class="stack" action="{{ localized_route('individuals.update-constituencies', $individual) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('individuals.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => $individual->isConnector() ? 5 : 4]) }}<br />
                {{ __('Groups you can connect to') }}
            </h2>

            <p class="repel">
                <button class="secondary" name="previous" value="1">{{ __('Save and previous') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>

            <fieldset class="field @error('lived_experience_connections') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('Disability and Deaf communities you’re connected to (required)') }}</legend>
                <x-hearth-hint for="lived_experience_connections">{{ __('Please select the disability and Deaf communities you can connect projects to.') }}</x-hearth-hint>
                <p x-cloak>
                    <button type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </p>
                <x-hearth-checkboxes name="lived_experience_connections" :options="$livedExperiences" :checked="old('lived_experience_connections', $individual->livedExperienceConnections->pluck('id')->toArray())" hinted="lived_experience_connections-hint" />
                <x-translatable-input name="other_lived_experience_connections" :model="$individual"  :label="__('Other disability or Deaf community your connected to (optional)')" />
                <x-hearth-error for="lived_experience_connections" />
            </fieldset>
            <fieldset class="field @error('constituency_connections') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('Other equity-seeking communities you’re connected to (optional)') }}</legend>
                <x-hearth-hint for="constituency_connections">{{ __('Please select the other equity-seeking communities you can connect projects to.') }}</x-hearth-hint>
                <p x-cloak>
                    <button type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </p>
                <x-hearth-checkboxes name="constituency_connections" :options="$constituencies" :checked="old('constituency_connections', $individual->constituencyConnections->pluck('id')->toArray())" hinted="constituency_connections-hint" />
                <x-hearth-error for="constituency_connections" />
                <x-translatable-input name="other_constituency_connections" :model="$individual" :label="__('Other equity-seeking community your connected to (optional)')" />
            </fieldset>
            <fieldset class="field @error('age_bracket_connections') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('Age groups you’re connected to (optional)') }}</legend>
                <x-hearth-hint for="age_bracket_connections">{{ __('Please select the age groups you can connect projects to.') }}</x-hearth-hint>
                <p x-cloak>
                    <button type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </p>
                <x-hearth-checkboxes name="age_bracket_connections" :options="$ageBrackets" :checked="old('age_bracket_connections', $individual->ageBracketConnections->pluck('id')->toArray())" hinted="age_bracket_connections-hint" />
                <x-hearth-error for="age_bracket_connections" />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="previous" value="1">{{ __('Save and previous') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>


</form>
