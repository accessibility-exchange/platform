<x-expander level="2" :summary="__('Change language')">
    <h3>{{ __('Available languages') }}</h3>
    <ul role="list">
    @foreach($model->languages as $code)
        @if(in_array($code, config('locales.supported')))
            <li><a href="{{ localized_route($model->getRoutePrefix() . '.show', $model, $code) }}">{{ get_language_exonym($code) }}</a></li>
        @else
            <li><a href="{{ localized_route($model->getRoutePrefix() . '.show', [Str::camel(class_basename(get_class($model))) => $model, 'language' => $code], get_written_language_for_signed_language($code)) }}">{{ get_language_exonym($code) }}</a></li>
        @endif
    @endforeach
    </ul>
    <h3>{{ __('Don’t see the language you need?') }}</h3>

    <p><a href="#TODO">{{ __('Contact support') }}</a></p>
</x-expander>
