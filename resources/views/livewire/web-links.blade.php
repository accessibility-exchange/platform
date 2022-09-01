<div class="stack">
    <ul class="stack" role="list">
        @foreach ($links as $i => $link)
            <li class="stack">
                <div class="field @error("{$name}.{$i}.title") field--error @enderror">
                    <x-hearth-label :for="$name . '_title_' . $i" :value="__('Website title')" />
                    <x-hearth-input :id="$name . '_title_' . $i" :name="$name . '[' . $i . '][title]'" :value="$link['title']" />
                    <x-hearth-error :for="$name . '_' . $i . '_title'" :field="$name . '.' . $i . '.title'" />
                </div>
                <div class="field @error("{$name}.{$i}.url") field--error @enderror">
                    <x-hearth-label :for="$name . '_url_' . $i" :value="__('Website link')" />
                    <x-hearth-input type="url" :id="'url_' . $i" :name="$name . '[' . $i . '][url]'" :value="$link['url']" />
                    <x-hearth-error :for="$name . '_' . $i . '_url'" :field="$name . '.' . $i . '.url'" />
                </div>
                <button class="secondary" type="button"
                    wire:click="removeLink({{ $i }})">{{ __('Remove this link') }}</button>
        @endforeach
    </ul>
    @if ($this->canAddMoreLinks())
        <button class="secondary" type="button" wire:click="addLink">{{ __('Add another link') }}</button>
    @endif
</div>
