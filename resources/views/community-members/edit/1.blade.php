@include('community-members.partials.progress')

<h2>
    {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 5]) }}<br />
    {{ __('About you') }}
</h2>


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

    <fieldset class="flow" x-data="otherLinks({{ count($communityMember->other_links ?? []) }})">
        <legend>{{ __('Other websites (optional)') }}</legend>

        <ul role="list" class="flow" x-ref="list">
            @if($communityMember->other_links)
                @forelse ($communityMember->other_links as $link)
                <li class="flow">
                    <div class="field @error('other_links.' . $loop->index . '.title') field--error @enderror">
                        <label for="{{ 'link_title_' . $loop->index }}">{{ __('Website title') }}</label>
                        <input
                            id="{{ 'link_title_' . $loop->index }}"
                            name="other_links[{{ $loop->index }}][title]"
                            value="{{ old('other_links.' . $loop->index . '.title', $link['title']) }}"
                            @error('other_links.' . $loop->index . '.title') aria-invalid="true" aria-describedby="{{ 'other_links_' . $loop->index . '_title-error'}}" @enderror
                        />
                        @error('other_links.' . $loop->index . '.title')
                        <x-hearth-error :for="'other_links_' . $loop->index . '_title'" :field="'other_links.' . $loop->index . '.title'">
                            {{ $message }}
                        </x-hearth-error>
                        @enderror
                    </div>
                    <div class="field @error('other_links.' . $loop->index . '.url') field--error @enderror">
                        <label for="{{ 'link_url_' . $loop->index }}">{{ __('Website link') }}</label>
                        <input
                            id="{{ 'link_url_' . $loop->index }}"
                            name="other_links[{{ $loop->index }}][url]"
                            value="{{ old('other_links.' . $loop->index . '.url', $link['url']) }}"
                            @error('other_links.' . $loop->index . '.url') aria-invalid="true" aria-describedby="{{ 'other_links_' . $loop->index . '_url-error'}}" @enderror
                        />
                        @error('other_links.' . $loop->index . '.url')
                        <x-hearth-error :for="'other_links_' . $loop->index . '_url'" :field="'other_links.' . $loop->index . '.url'">
                            {{ $message }}
                        </x-hearth-error>
                        @enderror
                    </div>
                    <button type="button" x-bind="remove">{{ __('Remove this link') }}</button>
                </li>
                @empty
                <li class="flow">
                    <div class="field">
                        <label for="link_title_0">{{ __('Website title') }}</label>
                        <input
                            id="link_title_0"
                            name="other_links[0][title]"
                            value=""
                        />
                    </div>
                    <div class="field">
                        <label for="link_url_0">{{ __('Website link') }}</label>
                        <input
                            id="link_url_0"
                            name="other_links[0][url]"
                            value=""
                        />
                    </div>
                </li>
                @endforelse
            @else
            <li class="flow">
                <div class="field">
                    <label for="link_title_0">{{ __('Website title') }}</label>
                    <input
                        id="link_title_0"
                        name="other_links[0][title]"
                        value=""
                    />
                </div>
                <div class="field">
                    <label for="link_url_0">{{ __('Website link') }}</label>
                    <input
                        id="link_url_0"
                        name="other_links[0][url]"
                        value=""
                    />
                </div>
            </li>
            @endif
        </ul>
        <button type="button" x-bind="add">{{ __('Add another link') }}</button>
    </fieldset>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('otherLinks', (initialLength = 0) => ({
            length: initialLength,
            remove: {
                ['@click'](e) {
                    const list = e.target.parentNode.parentNode;
                    e.target.parentNode.remove();
                    let i = 0;
                    const listItems = list.querySelectorAll('li');
                    Array.prototype.forEach.call(listItems, el => {
                        const titleLabel = el.querySelector('label[for^="link_title"]');
                        const urlLabel = el.querySelector('label[for^="link_url"]');
                        const titleInput = el.querySelector('input[id^="link_title"]');
                        const urlInput = el.querySelector('input[id^="link_url"]');
                        titleLabel.setAttribute('for', `link_title_${i}`);
                        urlLabel.setAttribute('for', `link_url_${i}`);
                        titleInput.setAttribute('id', `link_title_${i}`);
                        titleInput.setAttribute('name', `other_links[${i}][title]`);
                        urlInput.setAttribute('id', `link_url_${i}`);
                        urlInput.setAttribute('name', `other_links[${i}][url]`);
                        i++;
                    });
                    this.length = i;
                },
                ['x-show']() {
                    return this.length > 1;
                }
            },
            add: {
                ['@click']() {
                    const row = this.$refs.list.querySelector('li').cloneNode(true);
                    const inputs = row.querySelectorAll('input');
                    Array.prototype.forEach.call(inputs, el => {
                        el.value = '';
                    });
                    this.$refs.list.appendChild(row);
                    let i = 0;
                    const listItems = this.$refs.list.querySelectorAll('li');
                    Array.prototype.forEach.call(listItems, el => {
                        const titleLabel = el.querySelector('label[for^="link_title"]');
                        const urlLabel = el.querySelector('label[for^="link_url"]');
                        const titleInput = el.querySelector('input[id^="link_title"]');
                        const urlInput = el.querySelector('input[id^="link_url"]');
                        titleLabel.setAttribute('for', `link_title_${i}`);
                        urlLabel.setAttribute('for', `link_url_${i}`);
                        titleInput.setAttribute('id', `link_title_${i}`);
                        titleInput.setAttribute('name', `other_links[${i}][title]`);
                        urlInput.setAttribute('id', `link_url_${i}`);
                        urlInput.setAttribute('name', `other_links[${i}][url]`);
                        i++;
                    });
                    this.length = i;
                }
            },

        }));
    });
    </script>

    <p>
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
