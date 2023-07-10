<div class="stack">
    <ul class="stack" role="list">
        @foreach ($languages as $i => $language)
            <li class="flex flex-row flex-wrap items-center gap-6" wire:key="{{ $name }}-{{ $i }}">
                <x-hearth-select class="w-full lg:w-2/3" :id='"{$name}_{$i}"' :name='"{$name}[{$i}]"' :options="$availableLanguages"
                    :selected="$language" :aria-label="__('Language :number', ['number' => $i + 1])" />
                @if ($loop->count > 1)
                    <button class="secondary" type="button" aria-describedby='{{ "{$name}_{$i}" }}'
                        wire:click="removeLanguage({{ $i }})">{{ __('Remove this language') }}</button>
                @endif
                <x-hearth-error for="{{ $name }}.{{ $i }}" />
            </li>
        @endforeach
    </ul>
    @if ($this->canAddMoreLanguages())
        <button class="secondary" type="button" wire:click="addLanguage">
            @svg('heroicon-o-plus-circle', 'icon--lg')
            {{ count($languages) ? __('Add another language') : __('Add a language') }}
        </button>
    @endif
</div>
