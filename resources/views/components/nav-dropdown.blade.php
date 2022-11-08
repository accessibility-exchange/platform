<li {{ $attributes->merge(['class' => 'dropdown']) }} x-data="{ open: false }" @keyup.escape.window="open = false"
    @click.away="open = false" @close.stop="open = false">
    <button class="nav-button" @click="open = ! open" x-bind:aria-expanded="open.toString()">
        {{ $trigger }}
        @svg('heroicon-o-chevron-down', 'indicator')
    </button>

    <ul role="list" @click="open = false" x-cloak>
        {{ $content }}
    </ul>
</li>
