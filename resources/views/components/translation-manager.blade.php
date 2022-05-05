<x-expander level="2" :summary="__('Language settings')" x-data>
    @foreach($model->languages as $language)
        <div x-data="modal()">
            <p class="repel">{{ get_language_exonym($language) }}@if(count($model->languages) > 1)<button class="secondary" @click="showModal">{{ __('Remove') }}<span class="visually-hidden"> {{ get_language_exonym($language) }}</span></button>@endif</p>
            <template x-teleport="body">
                <div class="modal-wrapper" x-show="showingModal">
                    <form class="modal stack" action="{{ localized_route('translations.destroy') }}" method="post" @keydown.escape.window="hideModal">
                        <h3>{{ __('Remove language') }}</h3>

                        <p>{{ __('Are you sure you want to remove :language? Any translations that you’ve entered will be lost.', ['language' => get_language_exonym($language)]) }}</p>

                        <p class="repel">
                            <button type="button" @click="hideModal">{{ __('Cancel') }}</button>
                            <button class="secondary">{{ __('Remove') }}</button>
                        </p>

                        <x-hearth-input name="translatable_type" type="hidden" :value="get_class($model)" />
                        <x-hearth-input name="translatable_id" type="hidden" :value="$model->id" />
                        <x-hearth-input name="language" type="hidden" :value="$language" />

                        @csrf
                        @method('put')
                    </form>
                </div>
            </template>
        </div>
    @endforeach

    <div x-data="modal()">
        <button class="secondary" @click="showModal">{{ __('Add language') }}</button>
        <template x-teleport="body">
            <div class="modal-wrapper" x-show="showingModal">
                <form class="modal stack" action="{{ localized_route('translations.add') }}" method="post" @keydown.escape.window="hideModal">
                    <h3>{{ __('Add language') }}</h3>

                    <div class="field @error('new_language') field--error @enderror">
                        <x-hearth-label for="new_language">{{ __('Language') }}</x-hearth-label>
                        <x-hearth-select x-data="autocomplete()" name="new_language" :options="$availableLanguages" :selected="old('new_language', '')" required />
                    </div>

                    <p class="repel">
                        <button class="secondary" type="button" @click="hideModal">{{ __('Cancel') }}</button>
                        <button>{{ __('Add language') }}</button>
                    </p>

                    <x-hearth-input name="translatable_type" type="hidden" :value="get_class($model)" />
                    <x-hearth-input name="translatable_id" type="hidden" :value="$model->id" />

                    @csrf
                    @method('put')
                </form>
            </div>
        </template>
    </div>
</x-expander>
