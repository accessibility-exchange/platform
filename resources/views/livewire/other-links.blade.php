<div class="flow">
    <ul role="list" class="flow">
        @foreach($links as $i => $link)
        <li class="flow">
            <div class="field @error("other_links.{$i}.title") field--error @enderror">
                <x-hearth-label :for="'other_links_title_' . $i" :value="__('Website title')" />
                <x-hearth-input :id="'other_links_title_' . $i" :name="'other_links[' . $i . '][title]'" :value="$link['title']" />
                <x-hearth-error :for="'other_links_' . $i . '_title'" :field="'other_links.' . $i . '.title'" />
            </div>
            <div class="field @error("other_links.{$i}.url") field--error @enderror">
                <x-hearth-label :for="'other_links_url_' . $i" :value="__('Website link')" />
                <x-hearth-input type="url" :id="'url_' . $i" :name="'other_links[' . $i . '][url]'" :value="$link['url']" />
                <x-hearth-error :for="'other_links_' . $i . '_url'" :field="'other_links.' . $i . '.url'" />
            </div>
            <button type="button" wire:click="removeLink({{ $i }})">{{ __('Remove this website') }}</button>
        @endforeach
    </ul>
    @if ($this->canAddMoreLinks())
    <button type="button" wire:click="addLink">{{ __('Add another website') }}</button>
    @endif
</div>
