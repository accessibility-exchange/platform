<div class="stack">
    <ul role="list" class="stack">
        @foreach($languages as $i => $language)
        <li class="flex flex-row items-center gap-6" wire:key="{{ $name }}-{{ $i }}">
            <x-hearth-select :id='"{$name}_{$i}"' :name='"{$name}[{$i}]"' :options="$availableLanguages" :selected="$language" :aria-label="__('Language :number', ['number' => $i + 1])" />
            @if($loop->count > 1)
            <button class="secondary" type="button" wire:click="removeLanguage({{ $i }})" aria-describedby='{{ "{$name}_{$i}" }}'>{{ __('Remove this language') }}</button>
            @endif
        </li>
        @endforeach
    </ul>
    @if ($this->canAddMoreLanguages())
    <button class="secondary" type="button" wire:click="addLanguage">{{ __('Add a language') }}</button>
    @endif
</div>
