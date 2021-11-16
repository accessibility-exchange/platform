<div class="field @error($name . '.' . locale()) field--error @enderror">
    <x-hearth-label :for="$name . '_' . locale()" :value="__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name(locale())])" />
    <x-hearth-input type="text" :id="$name . '_' . locale()" :name="$name . '[' . locale() . ']'" :value="old($name . '.' . locale(), '')" />
    <x-hearth-error :for="$name . '.' . locale()" />
</div>
@foreach($locales as $locale)
    @if($locale !== locale())
    <div class="expander field @error($name . '.' . $locale) field--error @enderror" x-data="{expanded: false, value: '{{ old($name . '.' . $locale, '') }}'}">
        <p class="expander__summary" id="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($locale)])) }}">
            <button type="button" x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
                {{ __(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($locale)]) }} <span class="badge" x-show="value" x-text="value ? '{{ __('Content added') }}' : ''"></span> <x-heroicon-s-plus x-show="! expanded" aria-hidden="true" class="icon" /><x-heroicon-s-minus x-show="expanded" aria-hidden="true" class="icon" />
            </button>
        </p>
        <div class="expander__content" x-show="expanded">
            <x-hearth-input type="text" :id="$name . '_' . $locale" :name="$name . '[' . $locale . ']'" :value="old($name . '.' . $locale, '')" x-model="value" :aria-labelledby="Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_locale_name($locale)]))" />
            <x-hearth-error :for="$name . '.' . $locale" />
        </div>
    </div>
    @endif
@endforeach
