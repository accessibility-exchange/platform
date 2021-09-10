<div class="dropdown flow" x-data="{ open: false }" @keyup.escape.window="open = false" @click.away="open = false" @close.stop="open = false">
    <button class="link" @click="open = ! open" x-bind:aria-expanded="open.toString()">
        {{ $trigger }} <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" />
    </button>

    <div class="dropdown__content" x-show="open" x-cloak @click="open = false">
        {{ $content }}
    </div>
</div>
