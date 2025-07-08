@spaceless
    <div {{ $attributes->class(['stack']) }}>
        @if ($videoSrc)
            <div x-data="vimeoPlayer({
                url: @js($videoSrc),
                byline: false,
                dnt: true,
                pip: true,
                portrait: false,
                responsive: true,
                speed: true,
                title: false
            })" @ended="player().setCurrentTime(0)">
            </div>
        @endif
    </div>
@endspaceless
