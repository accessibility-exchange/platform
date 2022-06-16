<form class="stack" action="{{ localized_route('individuals.update', $individual) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('individuals.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                {{ __('About you') }}
            </h2>

            <p class="repel">
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>

            </p>

            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('Name (required)')" />
                <x-hearth-hint for="name">{{ __('This is the name that will be displayed on your page. This does not have to be your legal name.') }}</x-hearth-hint>
                <x-hearth-input type="text" name="name" :value="old('name', $individual->name)" required hinted />
                <x-hearth-error for="name" />
            </div>

            <fieldset>
                <legend>{{ __('Where do you live?') }}</legend>

                <div class="field @error('region') field--error @enderror">
                    <x-hearth-label for="region" :value="__('Province or territory (required)')" />
                    <x-hearth-select name="region" :options="$regions" :selected="old('region', $individual->region)" required />
                    <x-hearth-error for="region" />
                </div>

                <div class="field @error('locality') field--error @enderror">
                    <x-hearth-label for="locality" :value="__('City or town (optional)')" />
                    <x-hearth-input type="text" name="locality" value="{{ old('locality', $individual->locality) }}" />
                    <x-hearth-error for="locality" />
                </div>
            </fieldset>

            <div class="field @error('pronouns') field--error @enderror">
                <x-translatable-input name="pronouns" :model="$individual" :label="__('Pronouns (optional)')" :hint="__('For example: he/him, she/her, they/them.')" />
                <x-hearth-error for="pronouns" />
            </div>

            <fieldset>
                <div class="field @error('bio') field--error @enderror">
                    <x-translatable-textarea name="bio" :label="__('Your bio (required)')" :model="$individual" :hint="__('This can include information about your background, and why you are interested in accessibility.')" />
                    <x-hearth-error for="bio" />
                </div>

                {{-- TODO: Upload a file. --}}
            </fieldset>

            <div class="field @error('first_language') field--error @enderror stack">
                <x-hearth-label for="first_language" :value="__('What is your first language? (required)')" />
                <x-hearth-locale-select name="first_language" :selected="old('first_language', $individual->first_language ?? $individual->user->locale)" required />
                <x-hearth-error for="first_language" />
            </div>

            <fieldset>
                <legend>{{ __('What other languages are you comfortable working in?') }}</legend>
                <livewire:language-picker name="working_languages" :languages="$individual->working_languages ?? []" :availableLanguages="$languages" />
            </fieldset>

            @if($individual->isConnector())
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
            @endif

            <fieldset>
                <legend>{{ __('Social media links') }}</legend>
                @foreach ([
                    'linked_in',
                    'twitter',
                    'instagram',
                    'facebook'
                ] as $key)
                    <div class="field @error('social_links.' . $key) field--error @enderror">
                        <x-hearth-label for="social_links_{{ $key }}" :value="__(':service (optional)', ['service' => Str::studly($key)] )" />
                        <x-hearth-input id="social_links_{{ $key }}" name="social_links[{{ $key }}]" :value="old('social_links.' . $key, $individual->social_links[$key] ?? '')" />
                        <x-hearth-error for="social_links_{{ $key }}" />
                    </div>
                @endforeach
            </fieldset>

            <fieldset class="stack">
                <legend>{{ __('Other websites (optional)') }}</legend>
                <p class="field__hint">{{ __('This could be your personal website, a blog or portfolio, or articles about your work.') }}</p>
                <livewire:web-links :links="$individual->web_links ?? [['title' => '', 'url' => '']]" />
            </fieldset>

            <p class="repel">
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>


</form>
