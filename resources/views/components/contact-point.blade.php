<div class="contact-point">
    <div class="with-icon">
        @if ($type === App\Enums\ContactMethod::Email->value)
            @svg('heroicon-o-envelope')
        @else
            @svg('heroicon-o-phone')
        @endif
        <span><strong>{{ $label }}{{ $preferred ? ' (' . __('preferred') . ')' : '' }}:</strong>
            @if ($type === App\Enums\ContactMethod::Email->value)
                <a href="mailto:{{ $value }}">{{ $value }}</a>@else{{ $value }}
            @endif
            @if ($vrs)
                ({{ __('requires VRS') }})
            @endif
        </span>
    </div>
</div>
