<div class="stack">
    <ul class="stack" role="list">
        @foreach ($languages as $i => $language)
            <li class="flex flex-row items-center gap-6" wire:key="{{ $name }}-{{ $i }}">
                <x-hearth-select :id='"{$name}_{$i}"' :name='"{$name}[{$i}]"' :options="$availableLanguages" :selected="$language"
                    :aria-label="__('Language :number', ['number' => $i + 1])" />
                @if ($loop->count > 1)
                    <button class="secondary" type="button" aria-describedby='{{ "{$name}_{$i}" }}'
                        wire:click="removeLanguage({{ $i }})">{{ __('Remove this language') }}</button>
                @endif
            </li>
        @endforeach
    </ul>
    @if ($this->canAddMoreLanguages())
        <button class="secondary" type="button" wire:click="addLanguage">
            <x-heroicon-o-plus-circle class="h-6 w-6" />
            {{ count($languages) ? __('Add another language') : __('Add a language') }}
        </button>
    @endif
</div>
