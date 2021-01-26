<div class="dropdown" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <button @click="open = ! open" x-bind:aria-expanded="open.toString()">
        {{ $trigger }}
    </button>

    <div x-show="open"
        @click="open = false">
        {{ $content }}
    </div>
</div>
