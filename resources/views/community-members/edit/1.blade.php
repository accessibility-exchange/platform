<form class="stack" action="{{ localized_route('community-members.update', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')


    <div class="with-sidebar">

        @include('community-members.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 5]) }}<br />
                {{ __('About you') }}
            </h2>

            <p class="repel">
                <x-hearth-input type="submit" name="save" :value="__('Save')" />
                <x-hearth-input class="secondary" type="submit" name="save_and_next" :value="__('Save and next')" />
            </p>

            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('Name (required)')" />
                <x-hearth-hint for="name">{{ __('This is the name that will be displayed on your page. This does not have to be your legal name.') }}</x-hearth-hint>
                <x-hearth-input type="text" name="name" :value="old('name', $communityMember->name)" required hinted />
                <x-hearth-error for="name" />
            </div>

            <fieldset>
                <legend>{{ __('Where do you live?') }}</legend>

                <div class="field @error('region') field--error @enderror">
                    <x-hearth-label for="region" :value="__('Province or territory (required)')" />
                    <x-hearth-select name="region" :options="$regions" :selected="old('region', $communityMember->region)" required />
                    <x-hearth-error for="region" />
                </div>

                <div class="field @error('locality') field--error @enderror">
                    <x-hearth-label for="locality" :value="__('City or town (optional)')" />
                    <x-hearth-input type="text" name="locality" value="{{ old('locality', $communityMember->locality) }}" />
                    <x-hearth-error for="locality" />
                </div>
            </fieldset>

            <div class="field @error('pronouns') field--error @enderror">
                <x-translatable-input name="pronouns" :model="$communityMember" :label="__('Pronouns (optional)')" hinted="pronouns-hint" />
                <x-hearth-hint for="pronouns">{{ __('For example: he/him, she/her, they/them.') }}</x-hearth-hint>
                <x-hearth-error for="pronouns" />
            </div>

            <fieldset>
                <div class="field @error('bio') field--error @enderror">
                    <x-translatable-textarea name="bio" :label="__('Your bio (required)')" :model="$communityMember" hinted="bio-hint" />
                    <x-hearth-hint for="bio">{{ __('This can include information about your background, and why you are interested in accessibility.') }}</x-hearth-hint>
                    <x-hearth-error for="bio" />
                </div>

                {{-- TODO: Upload a file. --}}
            </fieldset>

            <fieldset>
                <legend>{{ __('What languages are you comfortable working in?') }}</legend>
                <livewire:language-picker name="working_languages" :languages="$communityMember->working_languages ?? [$communityMember->user->locale]" :availableLanguages="$languages" />
            </fieldset>

            @if($communityMember->isConnector())
            <fieldset class="field @error('lived_experience_connections') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('Disability and Deaf communities you’re connected to (required)') }}</legend>
                <span x-text="otherValue"></span>
                <x-hearth-hint for="lived_experience_connections">{{ __('Please select the disability and Deaf communities you can connect projects to.') }}</x-hearth-hint>
                <p x-cloak>
                    <button type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </p>
                <x-hearth-checkboxes name="lived_experience_connections" :options="$livedExperiences" :checked="old('lived_experience_connections', $communityMember->livedExperienceConnections->pluck('id')->toArray())" hinted="lived_experience_connections-hint" />
                <x-translatable-input name="other_lived_experience_connections" :model="$communityMember"  :label="__('Other disability or Deaf community your connected to (optional)')" />
                <x-hearth-error for="lived_experience_connections" />
            </fieldset>
            <fieldset class="field @error('community_connections') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('Other equity-seeking communities you’re connected to (optional)') }}</legend>
                <x-hearth-hint for="community_connections">{{ __('Please select the other equity-seeking communities you can connect projects to.') }}</x-hearth-hint>
                <p x-cloak>
                    <button type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </p>
                <x-hearth-checkboxes name="community_connections" :options="$communities" :checked="old('community_connections', $communityMember->communityConnections->pluck('id')->toArray())" hinted="community_connections-hint" />
                <x-hearth-error for="community_connections" />
                <x-translatable-input name="other_community_connections" :model="$communityMember" :label="__('Other equity-seeking community your connected to (optional)')" />
            </fieldset>
            <fieldset class="field @error('age_group_connections') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('Age groups you’re connected to (optional)') }}</legend>
                <x-hearth-hint for="age_group_connections">{{ __('Please select the age groups you can connect projects to.') }}</x-hearth-hint>
                <p x-cloak>
                    <button type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </p>
                <x-hearth-checkboxes name="age_group_connections" :options="$ageGroups" :checked="old('age_group_connections', $communityMember->ageGroupConnections->pluck('id')->toArray())" hinted="age_group_connections-hint" />
                <x-hearth-error for="age_group_connections" />
            </fieldset>
            @endif

            <fieldset>
                <legend>{{ __('Social media links') }}</legend>

                @foreach ([
                    'linkedin' => 'LinkedIn',
                    'twitter' => 'Twitter',
                    'instagram' => 'Instagram',
                    'facebook' => 'Facebook'
                ] as $key => $label)
                    <div class="field @error('links.' . $key) field--error @enderror">
                        <x-hearth-label for="links_{{ $key }}" :value="__(':service (optional)', ['service' => $label] )" />
                        <x-hearth-input id="links_{{ $key }}" name="links[{{ $key }}]" :value="old('links[' . $key . ']', $communityMember->links[$key] ?? '')" />
                        <x-hearth-error for="links_{{ $key }}" />
                    </div>
                @endforeach
            </fieldset>

            <fieldset class="stack">
                <legend>{{ __('Other websites (optional)') }}</legend>
                <p class="field__hint">{{ __('This could be your personal website, a blog or portfolio, or articles about your work.') }}</p>
                <livewire:other-links :links="$communityMember->other_links ?? [['title' => '', 'url' => '']]" />
            </fieldset>

            <p class="repel">
                <x-hearth-input type="submit" name="save" :value="__('Save')" />
                <x-hearth-input class="secondary" type="submit" name="save_and_next" :value="__('Save and next')" />
            </p>
        </div>
    </div>


</form>
