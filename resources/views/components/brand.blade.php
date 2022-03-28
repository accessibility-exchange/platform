<!-- Brand -->
<a class="brand" rel="home" href="{{ localized_route('welcome') }}">
    @if(locale() == 'en')
    <x-tae-logo-en class="logo" />
    <x-tae-logo-mono-en class="logo logo--themeable" />
    @elseif(locale() == 'fr')
    <x-tae-logo-fr class="logo" />
    <x-tae-logo-mono-fr class="logo logo--themeable" />
    @endif
    <span class="visually-hidden">{{ __('app.name') }}</span>
</a>
