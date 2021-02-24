@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'flow']) }}>
        <p class="center">{{ __('auth.error_intro') }}</p>

        @foreach ($errors->all() as $error)
            <x-alert type="error">
                <p>{{ $error }}</p>
            </x-alert>
        @endforeach
    </div>
@endif
