@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'flow']) }}>
        <p class="center">{{ __('hearth::auth.error_intro') }}</p>

        @foreach ($errors->all() as $error)
            <x-hearth-alert type="error">
                <p>{{ $error }}</p>
            </x-hearth-alert>
        @endforeach
    </div>
@endif
