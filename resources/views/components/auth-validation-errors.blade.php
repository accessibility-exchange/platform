@props(['errors'])

@if ($errors->any())
    <div role="alert" x-data="{ ready: false }" x-init="ready = true">
        <template x-if="ready">
            <x-hearth-alert type="error">
                {{ __('hearth::auth.error_intro') }}
                {{-- <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul> --}}
            </x-hearth-alert>
    </div>
    </template>
    </div>
@endif
