<div class="translation-picker stack" x-data="translationPicker([@foreach ($languages as $language){ code: '{{ $language }}', exonym: '{{ get_language_exonym($language) }}' }@if (!$loop->last), @endif @endforeach], {
    @foreach ($availableLanguages as $language)@if ($language['value'] !== '')'{{ $language['value'] }}': '{{ $language['label'] }}'@if (!$loop->last),
                @endif @endif @endforeach
})">
    <div class="languages">
        <template x-for="(language, index) in languages">
            <div
                class="flex h-14 w-full items-center justify-between border border-x-0 border-b-0 border-solid border-t-grey-3 first-of-type:border-t-0">
                <p class="repel w-full"><span x-text="language.exonym"></span><button class="secondary" type="button"
                        x-bind:data-index="index" @click="removeLanguage($event)"
                        x-show="languages.length > 1 && language.code !== '{{ locale() }}' && canRemove(language.code)">{{ __('Remove') }}<span
                            class="visually-hidden" x-text="language.exonym"></span></button></p>

                <input name="languages[]" type="hidden" x-bind:value="language.code" />
            </div>
        </template>
    </div>

    <div x-data="modal()">
        <button class="secondary" type="button" @click="showModal">{{ __('Add translation') }}</button>
        <template x-teleport="body">
            <div class="modal-wrapper" x-show="showingModal">
                <div class="modal stack" @keydown.escape.window="hideModal">
                    <h3>{{ __('Add language') }}</h3>

                    <div class="field @error('new_language') field--error @enderror">
                        <x-hearth-label for="new_language">{{ __('Language') }}</x-hearth-label>
                        <x-hearth-select name="new_language" :options="$availableLanguages" :selected="old('new_language', '')" required
                            x-model="newLanguage" />
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
                    if (this.newLanguage !== '') {
                        this.languages.push({
                            code: this.newLanguage,
                            exonym: this.exonyms[this.newLanguage]
                        });
                    }
                },
                canRemove(language) {
                    if (language === 'fr')
                        return this.languages.some((language) => language.code === 'en');
                    if (language === 'en')
                        return this.languages.some((language) => language.code === 'fr');
                    return true;
                }
            }))
        });
    </script>
</div>
