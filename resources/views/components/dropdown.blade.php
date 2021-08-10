<div class="dropdown flow" x-data="{ open: false }" @keyup.escape.window="open = false" @click.away="open = false" @close.stop="open = false">
    <button class="link" @click="open = ! open" x-bind:aria-expanded="open.toString()">
        {{ $trigger }}
    </button>

    <div class="dropdown__content" x-show="open"
        @click="open = false">
        {{ $content }}
    </div>
</div>
