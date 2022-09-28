<li {{ $attributes->merge(['class' => 'dropdown']) }} x-data="{ open: false }" @keyup.escape.window="open = false"
    @click.away="open = false" @close.stop="open = false">
    <button class="nav-button" @click="open = ! open" x-bind:aria-expanded="open.toString()">
        {{ $trigger }}
        <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" />
    </button>

    <ul role="list" @click="open = false">
        {{ $content }}
    </ul>
</li>
