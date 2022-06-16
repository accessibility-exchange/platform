<form class="stack" action="{{ localized_route('organizations.update', $organization) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                {{ __('About you') }}
            </h2>

            <p class="repel">
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>

            <h3>{{ __('Organization information') }}</h3>

            <div class="field @error('name') field--error @enderror">
                <x-translatable-input name="name" :model="$organization" :label="__('Organization name')" :hint="__('This is the name that will show up publicly on your page.')" required />
                <x-hearth-error for="name" />
            </div>

            <div class="field @error('about') field--error @enderror">
                <x-translatable-textarea name="about" :model="$organization" :label="__('About your organization (required)')" :hint="__('This can include your vision and mission, what your organization offers, etc.')" required />
            </div>

            <fieldset>
                <legend>{{ __('Your headquarters location (required)') }}</legend>

                <div class="field @error('region') field--error @enderror">
                    <x-hearth-label for="region" :value="__('Province or territory')" />
                    <x-hearth-select name="region" :options="$regions" :selected="old('region', $organization->region)" required />
                    <x-hearth-error for="region" />
                </div>

                <div class="field @error('locality') field--error @enderror">
                    <x-hearth-label for="locality" :value="__('City or town')" />
                    <x-hearth-input type="text" name="locality" value="{{ old('locality', $organization->locality) }}" required />
                    <x-hearth-error for="locality" />
                </div>
            </fieldset>

            <fieldset class="field @error('service_areas') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('What provinces or territories does your organization serve? (required)') }}</legend>
                <p class="stack" x-cloak>
                    <button class="secondary" type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button class="secondary" type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </p>
                <x-hearth-checkboxes name="service_areas" :options="array_filter($regions)" :checked="old('service_areas', $organization->service_areas ?? [])" required />
            </fieldset>

            <fieldset>
                <legend><h3>{{ __('What language(s) does your organization work in? (required)') }}</h3></legend>
                <livewire:language-picker name="working_languages" :languages="$organization->working_languages ?? []" :availableLanguages="$languages" />
            </fieldset>

            @if($organization->isConsultant())
            <fieldset class="field @error('consulting_services') field--error @enderror">
                <legend>{{ __('Which of these areas can you help a regulated organization with? (required)') }}</legend>
                <x-hearth-checkboxes name="consulting_services" :options="$consultingServices" :checked="old('consulting_services', $organization->consulting_services ?? [])" hinted="consulting_services-hint" required />
            </fieldset>
            @endif

            <h3>{{ __('Social media and website links (optional)') }}</h3>

            <fieldset class="stack">
                <legend><h4>{{ __('Social media') }}</h4></legend>
                @foreach ([
                    'linked_in',
                    'twitter',
                    'instagram',
                    'facebook'
                ] as $key)
                    <div class="field @error('social_links.' . $key) field--error @enderror">
                        <x-hearth-label for="social_links_{{ $key }}" :value="__(':service (optional)', ['service' => Str::studly($key)] )" />
                        <x-hearth-input id="social_links_{{ $key }}" name="social_links[{{ $key }}]" :value="old('social_links.' . $key, $organization->social_links[$key] ?? '')" />
                        <x-hearth-error for="social_links_{{ $key }}" />
                    </div>
                @endforeach
            </fieldset>

            <h4>{{ __('Organization website') }}</h4>

            <div class="field">
                <x-hearth-label for="website_link" :value="__('Website link (optional)')" />
                <x-hearth-input type="url" name="website_link" :value="old('website_link', $organization->website_link)" />
            </div>

            <p class="repel">
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>


</form>
