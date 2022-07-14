<div class="stack" x-data="translationPicker([ @foreach($languages as $language){ code: '{{ $language }}', exonym: '{{ get_language_exonym($language) }}' }@if(!$loop->last), @endif @endforeach ], {
                @foreach($availableLanguages as $language)@if($language['value'] !== '')'{{ $language['value'] }}': '{{ $language['label'] }}'@if(!$loop->last),
                @endif @endif @endforeach
    })">
    <template x-for="(language, index) in languages">
        <div x-data="modal()">
            <p class="repel"><span x-text="language.exonym"></span><button type="button" class="secondary" x-bind:data-index="index" @click="removeLanguage" x-show="languages.length > 1">{{ __('Remove') }}<span class="visually-hidden" x-text="language.exonym"></span></button></p>

            <input name="languages[]" type="hidden" x-bind:value="language.code" />
        </div>
    </template>

    <div x-data="modal()">
        <button type="button" class="secondary" @click="showModal">{{ __('Add language') }}</button>
        <template x-teleport="body">
            <div class="modal-wrapper" x-show="showingModal">
                <div class="modal stack" @keydown.escape.window="hideModal">
                    <h3>{{ __('Add language') }}</h3>

                    <div class="field @error('new_language') field--error @enderror">
                        <x-hearth-label for="new_language">{{ __('Language') }}</x-hearth-label>
                        <x-hearth-select name="new_language" :options="$availableLanguages" :selected="old('new_language', '')" required x-model="newLanguage" />
                    </div>

                    <p class="repel">
                        <button class="secondary" type="button" @click="hideModal">{{ __('Cancel') }}</button>
                        <button type="button" @click="addLanguage(); hideModal();">{{ __('Add language') }}</button>
                    </p>
                </div>
            </div>
        </template>
    </div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('translationPicker', (languages, exonyms) => ({
            languages: languages,
            exonyms: exonyms,
            newLanguage: '',
            removeLanguage(e) {
                this.languages.splice(e.target.dataset.index, 1);
            },
            addLanguage() {
                this.languages.push({code: this.newLanguage, exonym: this.exonyms[this.newLanguage]});
            }
        }))
    });
</script>
</div>

