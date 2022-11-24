@props(['contentSelector' => 'article', 'id' => 'tts-controller', 'enabled' => null])

@php
    $enabled ??= Auth::check() && Auth::user()->text_to_speech;
@endphp

@if ($enabled)
    <div id="{{ $id }}" {{ $attributes->merge([]) }}></div>

    @once
        @push('infusionScripts')
            <script src="{{ asset('build/assets/vendor/infusion/infusion-framework.js') }}"></script>
            <script src="{{ asset('build/assets/vendor/infusion/TextToSpeech.js') }}"></script>
            <script src="{{ asset('build/assets/vendor/infusion/orator/js/Orator.js') }}"></script>

            <link href="{{ asset('build/assets/vendor/infusion/orator/css/Orator.css') }}" rel="stylesheet">
        @endpush
    @endonce

    @push('infusionScripts')
        <script>
            fluid.orator('body', {
                selectors: {
                    content: "{{ $contentSelector }}",
                    parent: "#{{ $id }}"
                },
                components: {
                    controller: {
                        options: {
                            parentContainer: "{orator}.dom.parent",
                        }
                    }
                }
            });
        </script>
    @endpush
@endif
