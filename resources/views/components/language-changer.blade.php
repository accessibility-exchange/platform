@props([
    'testValue' => $model instanceof App\Models\Individual ? 'bio' : 'name',
])

@if (count($model->languages) > 1)
    <hr />
    <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row">
        <div class="flex">
            <label class="whitespace-nowrap" for='available_languages'>{{ __('Page also available in:') }}</label>
            <ul class="flex flex-wrap gap-3" id='available_languages' role="list">
                @foreach ($model->languages as $code)
                    @if ($code !== $currentLanguage)
                        @if (in_array($code, config('locales.supported')))
                            {{-- Make sure at least the model name is translated to avoid 404 errors. --}}
                            @if ($model->isTranslatableAttribute($testValue) || !empty($model->getTranslation($testValue, $code, false)))
                                <li class="ml-2"><a
                                        href="{{ $getLanguageLink($code) }}">{{ get_language_exonym($code) }}</a>
                                </li>
                            @endif
                        @else
                            <li><a href="{{ $getLanguageLink($code, true) }}">{{ get_language_exonym($code) }}</a>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>
        <div>
            <p>
                <a href="#contact">{{ __('Contact support') }}</a>
                {{ __(' if you need this in another language.') }}
            </p>
        </div>
    </div>
@endif
