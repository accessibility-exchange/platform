<svg class="icon" width="23" height="23" viewBox="0 0 24 24">
    @if($started)
    <circle fill="currentColor" stroke="currentColor" stroke-width="1" r="10" cx="12" cy="12" />
    <circle stroke="black" stroke-width="3" stroke-dasharray="{{ 7 * 2 * pi() }} {{ 7 * 2 * pi() }}" stroke-dashoffset="{{ 7 * 2 * pi() - 7 * 2 * pi() * $progress ?? 0 }}" fill="transparent" r="7" cx="12" cy="12" style="transform: rotate(-90deg); transform-origin: 50% 50%;" />
    @else
    <circle fill="transparent" stroke="currentColor" stroke-width="2" r="9" cx="12" cy="12" />
    @endif
</svg>
