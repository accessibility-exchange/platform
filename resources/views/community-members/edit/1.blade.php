<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 5]) }}<br />
    {{ __('About you') }}
</h2>

@include('community-members.partials.progress')

<form action="{{ localized_route('community-members.update', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <x-privacy-indicator level="public" :value="__('This information will be public. Any member of the website can find this information.')" />

    <fieldset>
        <div class="field @error('name') field--error @enderror">
            <x-hearth-label for="name" :value="__('Display name (required)')" />
            <x-hearth-input type="text" name="name" :value="old('name', $communityMember->name)" required hinted />
                <x-hearth-hint for="name">{{ __('This is the name that will be displayed on your page. This does not have to be your legal name.') }}</x-hearth-hint>
            <x-hearth-error for="name" />
        </div>
    </fieldset>

    <fieldset>
        <legend>{{ __('Where do you live?') }}</legend>

        <p class="field__hint" id="address-hint">{{ __('Your location will be used if entities are looking to get feedback on their services in a certain place.') }}</p>

        <div class="field @error('region') field--error @enderror">
            <x-hearth-label for="region" :value="__('Province or territory (required)')" />
            <x-hearth-select name="region" required :options="$regions" :selected="old('region', $communityMember->region)" hinted="address-hint" />
            <x-hearth-error for="region" />
        </div>

        <div class="field @error('locality') field--error @enderror">
            <x-hearth-label for="locality" :value="__('City or town (optional)')" />
            <x-hearth-input type="text" name="locality" value="{{ old('locality', $communityMember->locality) }}" required hinted="address-hint locality-privacy" />
            <x-hearth-error for="locality" />
        </div>

    </fieldset>

    <div class="field @error('pronouns') field--error @enderror">
        <x-hearth-label for="pronouns" :value="__('Pronouns (optional)')" />
        <x-hearth-hint for="pronouns">{{ __('For example: he/him, she/her, they/them.') }}</x-hearth-hint>
        <x-hearth-input type="text" name="pronouns" value="{{ old('pronouns', $communityMember->pronouns) }}" hinted />
        <x-hearth-error for="pronouns" />
    </div>

    <fieldset>
        <div class="field @error('bio') field--error @enderror">
            <x-hearth-label for="bio" :value="__('Your bio (optional)')" />
            {{-- <p><a href="#">{{ __('Show an example') }}</a></p> --}}
            <x-hearth-hint for="bio">{{ __('This can include information about your background, and why you are interested in accessibility.') }}</x-hearth-hint>
            <x-hearth-textarea name="bio" hinted>{{ old('bio', $communityMember->bio) }}</x-hearth-textarea>
            <x-hearth-error for="bio" />
        </div>

        {{-- TODO: Upload a file. --}}
    </fieldset>

    <h3>{{ __('Social media and website links (optional)') }}</h3>

    <fieldset>
        <legend>{{ __('Social media (optional)') }}</legend>

        @foreach ([
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'facebook' => 'Facebook'
        ] as $key => $label)
            <div class="field @error('links.' . $key) field--error @enderror">
                <x-hearth-label for="links_{{ $key }}" :value="__(':service link', ['service' => $label] )" />
                <x-hearth-input id="links_{{ $key }}" name="links[{{ $key }}]" :value="old('links[' . $key . ']', $communityMember->links[$key] ?? '')" />
            <x-hearth-error for="links_{{ $key }}" />
            </div>
        @endforeach
    </fieldset>

    <fieldset
        class="flow"
        x-data="{ links: [
            @if($communityMember->other_links)
            @foreach ($communityMember->other_links as $link)
            {
                title: '{{ addslashes($link['title']) }}',
                url: '{{ $link['url'] }}'
            }@if(!$loop->last),@endif
            @endforeach
            @else
            {
                title: '',
                url: ''
            }
            @endif
        ] }"
        x-init="$refs.ssr.remove()"
    >
        <legend>{{ __('Other websites (optional)') }}</legend>
        <div x-ref="ssr">
            @if($communityMember->other_links)
            @foreach ($communityMember->other_links as $link)
            <div class="flow">
                <div class="field">
                    <x-hearth-label :for="'link_title_' . $loop->index" :value="__('Website title')" />
                    <x-hearth-input :id="'link_title_' . $loop->index" :name="'other_links[' . $loop->index . '][title]'" :value="old('other_links[' . $loop->index . '][title]', $link['title'])" />
                </div>
                <div class="field">
                    <x-hearth-label :for="'link_url_' . $loop->index" :value="__('Website link')" />
                    <x-hearth-input :id="'link_url_' . $loop->index" :name="'other_links[' . $loop->index . '][url]'" :value="old('other_links[' . $loop->index . '][url]', $link['url'])" />
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <template x-for="(link, index) in links">
            <div class="flow">
                <div class="field">
                    <label x-bind:for="'link_title_' + index">{{ __('Website title') }}</label>
                    <input type="text" x-bind:id="'link_title_' + index" x-bind:name="'other_links['+ index + '][title]'" x-bind:value="link.title"/ >
                </div>
                <div class="field"><label x-bind:for="'link_url_' + index">{{ __('Website link') }}</label>
                    <input type="text" x-bind:id="'link_url_' + index" x-bind:name="'other_links['+ index + '][url]'" x-bind:value="link.url"/ >
                </div>
                <p x-show="index > 0"><button @click="links.splice(index, 1)" type="button">Delete this link</button></p>
            </div>
        </template>
        <p><button @click="links.push({})" type="button">Add new link</button></p>
    </fieldset>

    <p>
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
