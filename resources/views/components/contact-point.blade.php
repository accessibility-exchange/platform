<div class="contact-point">
    <div class="with-icon">
        @if ($type === 'email')
            @svg('heroicon-o-mail')
        @else
            @svg('heroicon-o-phone')
        @endif
        <span><strong>{{ $label }}@if ($preferred)
                    ({{ __('preferred') }})
                @endif:</strong>
            {!! $value !!}@if ($vrs)
                ({{ __('requires VRS') }})
            @endif
        </span>
    </div>
</div>
