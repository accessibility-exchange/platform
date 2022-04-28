@foreach($languages as $language)
    @if($loop->first)
        <div class="field @error($name . '.' . $language) field--error @enderror stack">
            @if($language === locale())
                <x-hearth-label :for="$name . '_' . $language" :value="$label" />
            @else
                <x-hearth-label :for="$name . '_' . $language" :value="$label . ' (' . get_locale_name($language) . ')'" />
            @endif
            @if($hint)
                <x-hearth-hint :for="$name">{{ $hint }}</x-hearth-hint>
            @endif
            <x-hearth-textarea :id="$name . '_' . $language" :name="$name . '[' . $language . ']'" :value="old($name . '.' . $language, $model ? $model->getTranslation($name, $language, false) : '')" :hinted="$name . '-hint'" />
            <x-hearth-error :for="$name . '.' . $language" />
        </div>
    @else
        @if (is_signed_language($language))
        @else
        <div class="expander field @error($name . '.' . $language) field--error @enderror stack" x-data="{expanded: false, value: '{{ old($name . '.' . $language, $model ? $model->getTranslation($name, $language, false) : '') }}', badgeText: '{{ __('Content added') }}'}">
            <p class="expander__summary" id="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($language)])) }}">
                <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded" aria-describedby="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($language)])) }}-status">
                    {{ __(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($language)]) }} <x-heroicon-s-plus x-show="! expanded" aria-hidden="true" /><x-heroicon-s-minus x-show="expanded" aria-hidden="true" />
                </button>
            </p>
            <span class="badge" id="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($language)])) }}-status" x-show="value && ! expanded" x-text="value ? badgeText : ''"></span>
            <div class="expander__content" x-show="expanded">
                <x-hearth-textarea :id="$name . '_' . $language" :name="$name . '[' . $language . ']'" :value="old($name . '.' . $language, $model ? $model->getTranslation($name, $language, false) : '')" :hinted="$name . '-hint'" x-model="value" x-on:keyup="badgeText = '{{ __('Content added, unsaved changes') }}'" :aria-labelledby="Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($language)]))" />
                <x-hearth-error :for="$name . '.' . $language" />
            </div>
        </div>
        @endif
    @endif
@endforeach
