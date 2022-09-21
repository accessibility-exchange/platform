@props(['errors'])

@if ($errors->any())
    <x-live-region>
        <x-hearth-alert type="error">
            {{ __('The following field is required:') }}
            <ul>
                @foreach ($errors->getBags()['default']->messages() as $key => $value)
                    @if ($key !== 'context' && $key !== 'locale')
                        <li>{{ ucfirst($key) }}</li>
                    @endif
                @endforeach
            </ul>
            {{ __('Please fill in this field and try saving again.') }}
        </x-hearth-alert>
    </x-live-region>
@endif
