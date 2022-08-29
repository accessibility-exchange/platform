<!-- Brand -->
<a class="brand" href="{{ localized_route('welcome') }}" rel="home">
    @if (locale() == 'en')
        <x-tae-logo-en class="logo" role="presentation" />
        <x-tae-logo-mono-en class="logo logo--themeable" role="presentation" />
    @elseif(locale() == 'fr')
        <x-tae-logo-fr class="logo" role="presentation" />
        <x-tae-logo-mono-fr class="logo logo--themeable" role="presentation" />
    @endif
    <span class="visually-hidden">{{ __('app.name') }}</span>
</a>
