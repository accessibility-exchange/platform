<form class="stack" action="{{ localized_route('individuals.update', $individual) }}" method="POST"
    enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('individuals.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => $individual->isConnector() ? 5 : 4]) }}<br />
                {{ __('About you') }}
            </h2>

            <hr class="divider--thick">

            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('Name') . ' ' . __('(required)')" />
                <x-hearth-hint for="name">
                    {{ __('This is the name that will be displayed on your page. This does not have to be your legal name.') }}
                </x-hearth-hint>
                <x-hearth-input name="name" type="text" :value="old('name', $individual->name)" required hinted />
                <x-hearth-error for="name" />
            </div>

            <fieldset>
                <legend>{{ __('Where do you live?') }}</legend>

                <div class="field @error('region') field--error @enderror">
                    <x-hearth-label for="region" :value="__('Province or territory') . ' ' . __('(required)')" />
                    <x-hearth-select name="region" :options="$regions" :selected="old('region', $individual->region)" required />
                    <x-hearth-error for="region" />
                </div>

                <div class="field @error('locality') field--error @enderror">
                    <x-hearth-label for="locality" :value="__('City or town') . ' ' . __('(optional)')" />
                    <x-hearth-input name="locality" type="text"
                        value="{{ old('locality', $individual->locality) }}" />
                    <x-hearth-error for="locality" />
                </div>
            </fieldset>

            <div class="field @error('pronouns') field--error @enderror">
                <x-translatable-input name="pronouns" :model="$individual" :label="__('Pronouns') . ' ' . __('(optional)')" :shortLabel="__('pronouns')"
                    :hint="__('For example: he/him, she/her, they/them, other.')" />
                <x-hearth-error for="pronouns" />
            </div>

            <fieldset>
                <div class="field @error('bio') field--error @enderror">
                    <x-translatable-textarea name="bio" :label="__('Your bio') . ' ' . __('(required)')" :shortLabel="__('bio')" :model="$individual"
                        :hint="__(
                            'This can include information about your background, and why you are interested in accessibility.',
                        )" required />
                    <x-hearth-error for="bio" />
                </div>

                {{-- TODO: Upload a file. --}}
            </fieldset>

            <fieldset>
                <legend>{{ __('What language(s) are you comfortable working in?') }} {{ __('(optional)') }}</legend>
                <livewire:language-picker name="working_languages" :languages="old(
                    'working_languages',
                    !empty($individual->working_languages) ? $individual->working_languages : $workingLanguages,
                )" :availableLanguages="$languages" />
            </fieldset>

            @if ($individual->isConsultant())
                <fieldset class="field @error('consulting_services') field--error @enderror">
                    <legend>
                        {{ __('How can you help a regulated organization?') . ' ' . __('(required)') }}
                    </legend>
                    <x-hearth-checkboxes name="consulting_services" :options="$consultingServices" :checked="old('consulting_services', $individual->consulting_services ?? [])"
                        hinted="consulting_services-hint" required />
                    <x-hearth-error for="consulting_services" />
                </fieldset>
            @endif

            <fieldset>
                <legend>{{ __('Social media links') }}</legend>
                <x-hearth-hint for="social_links">
                    {{ __('Website links must be in the format “https://example.com”, or “example.com”.') }}
                </x-hearth-hint>
                @foreach (['linked_in', 'twitter', 'instagram', 'facebook'] as $key)
                    <div class="field @error('social_links.' . $key) field--error @enderror">
                        <x-hearth-label for="social_links_{{ $key }}" :value="__(':service', ['service' => Str::studly($key)]) . ' ' . __('(optional)')" />
                        <x-hearth-input id="social_links_{{ $key }}" name="social_links[{{ $key }}]"
                            type="url" :value="old('social_links.' . $key, $individual->social_links[$key] ?? '')" hinted="social_links-hint" />
                        <x-hearth-error for="social_links_{{ $key }}"
                            field="social_links.{{ $key }}" />
                    </div>
                @endforeach
            </fieldset>

            <div class="field @error('website_link') field-error @enderror">
                <x-hearth-label class="h4" for="website_link" :value="__('Website link') . ' ' . __('(optional)')" />
                <x-hearth-hint
                    for="website_link">{{ __('This could be your personal website, blog or portfolio.') }}<br />{{ __('Website links must be in the format “https://example.com”, or “example.com”.') }}
                </x-hearth-hint>
                <x-hearth-input name="website_link" type="url" :value="old('website_link', $individual->website_link)" hinted />
                <x-hearth-error for="website_link" />
            </div>

            <hr class="divider--thick">

            <p class="flex flex-wrap gap-7">
                <button class="secondary" name="save" value="1">{{ __('Save') }}</button>
                <button name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>

</form>
