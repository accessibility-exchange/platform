<p {{ $attributes->merge(['class' => 'field__privacy field__privacy--' . $level]) }}>
    @if($level === 'public')
    <x-heroicon-s-lock-open class="icon" aria-hidden="true" />
    @elseif($level === 'private')
    <x-heroicon-s-lock-closed class="icon" aria-hidden="true" />
    @endif {{ $value ?? $slot }}
</p>
