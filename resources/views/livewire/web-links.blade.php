<div class="stack">
    <ul role="list" class="stack">
        @foreach($links as $i => $link)
        <li class="stack">
            <div class="field @error("web_links.{$i}.title") field--error @enderror">
                <x-hearth-label :for="'web_links_title_' . $i" :value="__('Website title')" />
                <x-hearth-input :id="'web_links_title_' . $i" :name="'web_links[' . $i . '][title]'" :value="$link['title']" />
                <x-hearth-error :for="'web_links_' . $i . '_title'" :field="'web_links.' . $i . '.title'" />
            </div>
            <div class="field @error("web_links.{$i}.url") field--error @enderror">
                <x-hearth-label :for="'web_links_url_' . $i" :value="__('Website link')" />
                <x-hearth-input type="url" :id="'url_' . $i" :name="'web_links[' . $i . '][url]'" :value="$link['url']" />
                <x-hearth-error :for="'web_links_' . $i . '_url'" :field="'web_links.' . $i . '.url'" />
            </div>
            @if($loop->count > 1)
            <button class="secondary" type="button" wire:click="removeLink({{ $i }})">{{ __('Remove this website') }}</button>
            @endif
        @endforeach
    </ul>
    @if ($this->canAddMoreLinks())
    <button class="secondary" type="button" wire:click="addLink">{{ __('Add another website') }}</button>
    @endif
</div>
