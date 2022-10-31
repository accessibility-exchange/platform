<!-- Brand -->
<a class="brand" href="{{ localized_route('welcome') }}" rel="home">
    @if (locale() == 'en')
        @svg('tae-logo-en', ['class' => 'logo'])
        @svg('tae-logo-mono-en', ['class' => 'logo logo--themeable'])
    @elseif(locale() == 'fr')
        @svg('tae-logo-fr', ['class' => 'logo'])
        @svg('tae-logo-mono-fr', ['class' => 'logo logo--themeable'])
    @endif
    <span class="visually-hidden">{{ __('app.name') }}</span>
</a>
