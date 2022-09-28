@props(['errors'])

@if ($errors->any())
    <x-live-region>
        <x-hearth-alert type="error">
            {{ __('hearth::auth.error_intro') }}
            {{-- TODO: Break down errors, link to fields
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul> --}}
        </x-hearth-alert>
    </x-live-region>
@endif
