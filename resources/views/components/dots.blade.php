<span {{ $attributes->merge(['class' => '']) }}>
    @foreach (range(1, 5) as $i)
        <svg role="presentation" viewBox="0 0 24 24" width="18" height="18">
            <circle cx="12" cy="12" r="11" fill="transparent" stroke="currentColor" />
            @if ($value > 0)
                @if ($value > 0.5)
                    <circle cx="12" cy="12" r="11" fill="currentColor" stroke="currentColor" />
                @else
                    <path d="M12,1 a1,1 0 0,0 0,22" fill="currentColor" />
                @endif
            @endif
        </svg>
        @php $value--; @endphp
    @endforeach
</span>
