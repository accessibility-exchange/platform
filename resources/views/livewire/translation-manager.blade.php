<x-expander level="2" :summary="__('Language settings')">
    @foreach($model->languages as $language)
        <div x-data="{modal: false}">
            <p class="repel">{{ get_language_exonym($language) }}@if(count($model->languages) > 1)<button class="secondary" x-on:click="modal = true">{{ __('Remove') }}<span class="visually-hidden"> {{ get_language_exonym($language) }}</span></button>@endif</p>
            @if(count($model->languages) > 1)
            <form class="stack" action="{{ localized_route('translations.destroy') }}" method="post" x-show="modal">
                @csrf
                @method('put')

                <h3>{{ __('Remove language') }}</h3>

                <p>{{ __('Are you sure you want to remove :language? Any translations that you’ve entered will be lost.', ['language' => get_language_exonym($language)]) }}</p>

                <p class="repel">
                    <button type="button" x-on:click="modal = false">{{ __('Cancel') }}</button>
                    <x-hearth-button class="secondary" type="submit">{{ __('Remove') }}</x-hearth-button>
                </p>

                <x-hearth-input name="translatable_type" type="hidden" :value="get_class($model)" />
                <x-hearth-input name="translatable_id" type="hidden" :value="$model->id" />
                <x-hearth-input name="language" type="hidden" :value="$language" />
            </form>
        </div>
        @endif
    @endforeach
    <div x-data="{addingLanguage: false}">
        <button class="secondary" x-on:click="addingLanguage = true">{{ __('Add language') }}</button>
        <form class="stack" action="{{ localized_route('translations.add') }}" method="post" x-show="addingLanguage">
            @csrf
            @method('put')

            <h3>{{ __('Add language') }}</h3>

            <div class="field @error('new_language') field--error @enderror">
                <x-hearth-label for="new_language">{{ __('Language') }}</x-hearth-label>
                <x-hearth-select x-data="autocomplete()" name="new_language" :options="$availableLanguages" :selected="old('new_language', '')" required />
            </div>

            <p class="repel">
                <button class="secondary" type="button" x-on:click="modal = false">{{ __('Cancel') }}</button>
                <x-hearth-button type="submit">{{ __('Add language') }}</x-hearth-button>
            </p>

            <x-hearth-input name="translatable_type" type="hidden" :value="get_class($model)" />
            <x-hearth-input name="translatable_id" type="hidden" :value="$model->id" />
        </form>
    </div>
</x-expander>
