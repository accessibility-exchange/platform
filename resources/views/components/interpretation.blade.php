@spaceless
    <div id="{{ $id }}" {{ $attributes->class(['interpretation', 'expander', 'stack']) }} x-data="{ expanded: false }">
        @if ($videoSrc)
            <button class="borderless" type="button" x-bind:aria-expanded="expanded.toString()"
                x-on:click="expanded = !expanded">
                @svg('tae-sign-language', 'icon--xl')
                {{ __('Sign Language video') }}
            </button>
            <div class="stack interpretation__video" x-data="vimeoPlayer({
                url: '{{ $videoSrc }}',
                byline: false,
                pip: true,
                portrait: false,
                responsive: true,
                speed: true,
                title: false
            })" x-init="$watch('expanded', value => togglePlayback(value))"
                @ended="player().setCurrentTime(0)" x-show="expanded" x-cloak>
            </div>
        @endif
    </div>
@endspaceless
