<div class="field @error($name . '.' . locale()) field--error @enderror stack">
    <x-hearth-label :for="$name . '_' . locale()" :value="__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name(locale())])" />
    <x-hearth-input type="text" :id="$name . '_' . locale()" :name="$name . '[' . locale() . ']'" :value="old($name . '.' . locale(), $model ? $model->getTranslation($name, locale(), false) : '')" />
    <x-hearth-error :for="$name . '.' . locale()" />
</div>
@foreach($locales as $locale)
    @if($locale !== locale())
    <div class="expander field @error($name . '.' . $locale) field--error @enderror stack" x-data="{expanded: false, value: '{{ old($name . '.' . $locale, $model ? $model->getTranslation($name, $locale, false) : '') }}', badgeText: '{{ __('Content added') }}'}">
        <p class="expander__summary" id="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($locale)])) }}">
            <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded" aria-describedby="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($locale)])) }}-status">
                {{ __(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($locale)]) }} <x-heroicon-s-plus x-show="! expanded" aria-hidden="true" /><x-heroicon-s-minus x-show="expanded" aria-hidden="true" />
            </button>
        </p>
        <span class="badge" id="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($locale)])) }}-status" x-show="value && ! expanded" x-text="value ? badgeText : ''"></span>
        <div class="expander__content" x-show="expanded">
            <x-hearth-input type="text" :id="$name . '_' . $locale" :name="$name . '[' . $locale . ']'" :value="old($name . '.' . $locale, $model ? $model->getTranslation($name, $locale, false) : '')" x-model="value" x-on:keyup="badgeText = '{{ __('Content added, unsaved changes') }}'" :aria-labelledby="Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($locale)]))" />
            <x-hearth-error :for="$name . '.' . $locale" />
        </div>
    </div>
    @endif
@endforeach
