<x-slot name="title">
    {{ __('Module') . '-' . $module->title }}
</x-slot>

<x-slot name="header">
    <a href="{{ localized_route('courses.show', $module->course->id) }}">{{ __('Back') }} >
        {{ $module->course->title }}</a>
    <h1 id="module-title">
        {{ $module->title }}
    </h1>
</x-slot>

<div>
    <div class="stack ml-2 mr-2">
        <iframe src={{ 'https://player.vimeo.com/video/' . $module->video . '?h=e7306e5863' }} title="vimeo-player"
            width="640" height="400" frameborder="0" webkitallowfullscreen mozallowfullscreen
            allowfullscreen></iframe>
        <p>{{ $module->introduction }}</p>
    </div>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script>
        const iframe = document.querySelector('iframe');
        const player = new Vimeo.Player(iframe);
        player.on('ended', onPlayerEnded);
        player.on('play', onPlayerStarted);

        function onPlayerStarted(event) {
            Livewire.emit('onPlayerStart');
        }

        function onPlayerEnded(event) {
            Livewire.emit('onPlayerEnd');
        }

        function stopVideo() {
            player.stopVideo();
        }
    </script>
</div>
