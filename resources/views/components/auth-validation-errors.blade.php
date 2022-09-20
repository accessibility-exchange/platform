@props(['errors'])

@if ($errors->any())
    <x-live-region>
        <x-hearth-alert type="error">
            {{ __('The following field is required:') }}
            <ul>
                @foreach ($errors->getBags()['default']->messages() as $key => $value)
                    <li>{{ ucfirst($key) }}</li>
                @endforeach
            </ul>
        </x-hearth-alert>
    </x-live-region>
@endif
