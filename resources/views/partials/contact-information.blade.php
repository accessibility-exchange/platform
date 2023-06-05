<p>
    <strong>{{ __('Email') }}:</strong> <a href="mailto:{{ settings('email') }}">{{ settings('email') }}</a>
    <br>
    <strong>{{ safe_inlineMarkdown('Call or :!vrs', [
        'vrs' =>
            '<a href="https://srvcanadavrs.ca/en/resources/resource-centre/vrs-basics/register/" rel="external">' .
            htmlentities(__('VRS')) .
            '</a>',
    ]) }}:</strong>
    {{ phone(settings('phone'), 'CA')->formatForCountry('CA') }}
</p>
