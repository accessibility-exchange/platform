<div class="contact-point">
    <div class="with-icon">
        @if ($type === 'email')
            @svg('heroicon-o-mail')
        @else
            @svg('heroicon-o-phone')
        @endif
        <span><strong>{{ $label }}{{ $preferred ? ' (' . __('preferred') . ')' : '' }}:</strong>
            @if ($type === 'email')
                <a href="mailto:{{ $value }}">{{ $value }}</a>@else{{ $value }}
            @endif
            @if ($vrs)
                ({{ __('requires VRS') }})
            @endif
        </span>
    </div>
</div>
