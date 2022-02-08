@props(['errors'])

@if ($errors->any())
    <div x-data="{ready: false}" x-init="ready = true" role="alert">
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
