<div {{ $attributes->merge(['class' => 'stack']) }}>
    @foreach ($languages as $language)
        @if ($loop->first)
            <div class="field @error($name . '.' . $language) field--error @enderror stack">
                @if ($language === locale())
                    <x-hearth-label :for="$name . '_' . $language" :value="$label" />
                @else
                    <x-hearth-label :for="$name . '_' . $language" :value="$label . ' (' . get_language_exonym($language) . ')'" />
                @endif
                @if ($hint)
                    <x-hearth-hint :for="$name">{{ $hint }}</x-hearth-hint>
                @endif
                @isset($interpretationName)
                    @isset($interpretationNameSpace)
                        <x-interpretation class="interpretation--start" name="{{ __($interpretationName, [], 'en') }}"
                            namespace="{{ $interpretationNameSpace }}" />
                    @else
                        <x-interpretation class="interpretation--start" name="{{ __($interpretationName, [], 'en') }}" />
                    @endisset
                @endisset
                <x-hearth-textarea :id="$name . '_' . $language" :name="$name . '[' . $language . ']'" :value="old($name . '.' . $language, $model ? $model->getTranslation($name, $language, false) : '')" :hinted="$name . '-hint'"
                    :required="$required" />
                <x-hearth-error :for="$name . '.' . $language" />
            </div>
        @else
            @if (is_signed_language($language))
            @else
                <div class="expander field @error($name . '.' . $language) field--error @enderror stack"
                    x-data="{ expanded: false, value: '{{ old($name . '.' . $language, $model ? $model->getTranslation($name, $language, false) : '') }}', badgeText: '{{ __('Content added') }}' }">
                    <p class="title"
                        id="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_language_exonym($language)])) }}">
                        <button type="button"
                            aria-describedby="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_language_exonym($language)])) }}-status"
                            x-bind:aria-expanded="expanded.toString()" x-on:click="expanded = !expanded">
                            {{ __('Add :locale translation of :label', ['label' => $shortLabel ?? $label, 'locale' => get_language_exonym($language)]) }}
                            @svg('heroicon-s-plus', ['x-show' => '! expanded'])
                            @svg('heroicon-s-minus', ['x-show' => 'expanded'])
                        </button>
                    </p>
                    <span class="badge"
                        id="{{ Str::slug(__(':label (:locale)', ['label' => $label, 'locale' => get_language_exonym($language)])) }}-status"
                        x-show="value && ! expanded" x-text="value ? badgeText : ''"></span>
                    <div class="expander__content" x-show="expanded">
                        <x-hearth-textarea :id="$name . '_' . $language" :name="$name . '[' . $language . ']'" :value="old(
                            $name . '.' . $language,
                            $model ? $model->getTranslation($name, $language, false) : '',
                        )" :hinted="$name . '-hint'"
                            x-model="value" x-on:keyup="badgeText = '{{ __('Content added, unsaved changes') }}'"
                            :aria-labelledby="Str::slug(
                                __('Add :locale translation of :label', [
                                    'label' => $shortLabel ?? $label,
                                    'locale' => get_language_exonym($language),
                                ]),
                            )" />
                        <x-hearth-error :for="$name . '.' . $language" />
                    </div>
                </div>
            @endif
        @endif
    @endforeach
</div>
