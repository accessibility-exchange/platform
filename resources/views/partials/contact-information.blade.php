<p>
    <strong>{{ __('Email') }}:</strong> <a
        href="mailto:{{ settings_localized('email', locale()) }}">{{ settings_localized('email', locale()) }}</a>
    <br>
    <strong>{{ safe_inlineMarkdown('Call or :!vrs', [
        'vrs' => '<a href="' . settings_localized('vrs', locale()) . '" rel="external">' . htmlentities(__('VRS')) . '</a>'
    ]) }}:</strong>
    {{ phone(settings_localized('phone', locale(), '+1-888-867-0053'), 'CA')->formatForCountry('CA') }}
</p>
