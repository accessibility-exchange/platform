@if (locale() == 'en' || locale() == 'asl')
    @svg('tae-logo-en', ['class' => 'logo h-10'])
    @svg('tae-logo-mono-en', ['class' => 'logo logo--themeable h-10'])
@elseif(locale() == 'fr' || locale() == 'lsq')
    @svg('tae-logo-fr', ['class' => 'logo h-10'])
    @svg('tae-logo-mono-fr', ['class' => 'logo logo--themeable h-10'])
@endif
