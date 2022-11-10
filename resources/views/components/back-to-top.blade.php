@props([
    'width' => null,
])

<div class="back-to-top full">
    <div class="center @if ($width) center:{{ $width }} @endif">
        <a class="with-icon" href="#main">@svg('heroicon-o-arrow-up') {{ __('Back to top') }}</a>
    </div>
</div>
