<!-- Brand -->
<a class="brand" href="{{ localized_route('welcome') }}" rel="home">
    <span class="hidden md:block">
        @if (locale() == 'en' || locale() == 'asl')
            @svg('tae-logo-en', ['class' => 'logo'])
            @svg('tae-logo-mono-en', ['class' => 'logo logo--themeable'])
        @elseif(locale() == 'fr' || locale() == 'lsq')
            @svg('tae-logo-fr', ['class' => 'logo'])
            @svg('tae-logo-mono-fr', ['class' => 'logo logo--themeable'])
        @endif
    </span>
    <span class="block md:hidden">
        @svg('tae-mark', ['class' => 'logo'])
        @svg('tae-mark-mono', ['class' => 'logo logo--themeable'])
    </span>
    <span class="visually-hidden">{{ __('app.name') }}</span>
</a>
