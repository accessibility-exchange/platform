<p {{ $attributes->merge(['class' => 'field__privacy']) }}>
    <x-heroicon-s-lock-closed class="icon" aria-hidden="true" /> {{ $value ?? $slot }}
</p>
