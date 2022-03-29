<div class="stack">
    <ul role="list" class="stack">
        @foreach($languages as $i => $language)
        <li class="stack">
            <x-hearth-select :id='"languages_{$i}"' :name='"languages[{$i}]"' :options="$availableLanguages" :selected="$language" :aria-label="__('Language :number', ['number' => $i + 1])" />
            @if($loop->count > 1)
            <button type="button" wire:click="removeLanguage({{ $i }})" aria-describedby='{{ "languages_{$i}" }}'>{{ __('Remove this language') }}</button>
            @endif
        </li>
        @endforeach
    </ul>
    @if ($this->canAddMoreLanguages())
    <button type="button" wire:click="addLanguage">{{ __('Add another language') }}</button>
    @endif
</div>
