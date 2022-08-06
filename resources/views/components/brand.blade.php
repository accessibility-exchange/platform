<!-- Brand -->
<a class="brand" rel="home" href="{{ localized_route('welcome') }}">
    @if(locale() == 'en')
    <x-tae-logo-en role="presentation" class="logo" />
    <x-tae-logo-mono-en role="presentation" class="logo logo--themeable" />
    @elseif(locale() == 'fr')
    <x-tae-logo-fr role="presentation" class="logo" />
    <x-tae-logo-mono-fr role="presentation" class="logo logo--themeable" />
    @endif
    <span class="visually-hidden">{{ __('app.name') }}</span>
</a>
