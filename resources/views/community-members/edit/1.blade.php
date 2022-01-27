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

    <fieldset class="flow" x-data="otherLinks(
        {{ count($communityMember->other_links ?? []) }},
        'other_links',
        ['title', 'url']
    )">
        <legend>{{ __('Other websites (optional)') }}</legend>

        <ul role="list" class="flow" x-ref="list">
            @if($communityMember->other_links && count($communityMember->other_links) > 0)
                @foreach ($communityMember->other_links as $link)
                <li class="flow">
                    <div class="field @error('other_links.' . $loop->index . '.title') field--error @enderror">
                        <label for="{{ 'other_links_title_' . $loop->index }}">{{ __('Website title') }}</label>
                        <input
                            id="{{ 'other_links_title_' . $loop->index }}"
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
                        <label for="{{ 'other_links_url_' . $loop->index }}">{{ __('Website link') }}</label>
                        <input
                            id="{{ 'other_links_url_' . $loop->index }}"
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
                @endforeach
            @else
                <li class="flow">
                    <div class="field">
                        <label for="other_links_title_0">{{ __('Website title') }}</label>
                        <input
                            id="other_links_title_0"
                            name="other_links[0][title]"
                            value=""
                        />
                    </div>
                    <div class="field">
                        <label for="other_links_url_0">{{ __('Website link') }}</label>
                        <input
                            id="other_links_url_0"
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
        Alpine.data('otherLinks', (length = 0, name = false, fields = []) => ({
            length: length,
            name: name,
            fields: fields,
            reindex(items) {
                let i = 0;
                Array.prototype.forEach.call(items, el => {
                    this.fields.forEach(field => {
                        const label = el.querySelector(`label[for^="${this.name}_${field}`);
                        const input = el.querySelector(`input[id^="${this.name}_${field}`);
                        label.setAttribute('for', `${this.name}_${field}_${i}`);
                        input.setAttribute('id', `${this.name}_${field}_${i}`);
                        input.setAttribute('name', `${this.name}[${i}][${field}]`);
                    });
                    i++;
                });
                return i;
            },
            remove: {
                ['@click'](e) {
                    const list = e.target.parentNode.parentNode;
                    e.target.parentNode.remove();
                    const listItems = list.querySelectorAll('li');
                    this.length = this.reindex(listItems);
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
                    const listItems = this.$refs.list.querySelectorAll('li');
                    this.length = this.reindex(listItems);
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
