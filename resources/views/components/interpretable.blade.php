<div id="{{ $id }}">
    {{ $slot }}

    @if ($videoSrc)
        <div class="expander stack" x-data="{ expanded: false }">
            <button class="borderless" type="button" x-bind:aria-expanded="expanded.toString()"
                x-on:click="expanded = !expanded">
                @svg('tae-sign-language', 'icon--xl')
                {{ __('Sign Language video') }}
            </button>
            <div class="stack" data-vimeo-url="{{ $videoSrc }}" data-vimeo-autoplay="true" data-vimeo-byline="false"
                data-vimeo-maxwidth="770" data-vimeo-pip="true" data-vimeo-portrait="false" data-vimeo-responsive="true"
                data-vimeo-speed="true" data-vimeo-title="false" x-show="expanded" x-cloak>
            </div>
        </div>
    @endif
</div>
