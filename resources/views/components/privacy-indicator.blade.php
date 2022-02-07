<p {{ $attributes->merge(['class' => 'field__privacy field__privacy--' . $level]) }}>
    <strong>
    @if($level === 'public')
    <x-heroicon-s-lock-open class="icon" aria-hidden="true" /> {{ __('This information will be public.') }}
    @elseif($level === 'private')
    <x-heroicon-s-lock-closed class="icon" aria-hidden="true" /> {{ __('This information will be private.') }}
    @endif
    </strong><br />
    {{ $value ?? $slot }}
</p>
