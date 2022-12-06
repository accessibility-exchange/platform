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
            // Re-defining as a hack to work around this method not being accessed as an invoker by the
            // selectionReader, and thus not easily able to override.
            // see: https://issues.fluidproject.org/browse/FLUID-6757
            fluid.orator.selectionReader.renderControlState = function(that, control) {
                var text = that.options.strings[that.model.play ? "stop" : "play"];
                control.attr("aria-label", text);
                control.toggleClass(that.options.styles.playing, that.model.play);
            };

            fluid.orator('body', {
                selectors: {
                    content: $("{{ $contentSelector }}").first(),
                    parent: "#{{ $id }}"
                },
                strings: {
                    play: "{{ __('Play') }}",
                    pause: "{{ __('Pause') }}",
                    stop: "{{ __('Stop') }}",
                },
                controller: {
                    parentContainer: "{orator}.dom.parent",
                    styles: {
                        play: "tts-controller--playing"
                    },
                    markup: {
                        container: `<x-tts.controller />`
                    }
                },
                domReader: {
                    markup: {
                        highlight: `<x-tts.mark />`
                    }
                },
                selectionReader: {
                    styles: {
                        above: "tts-selection--above",
                        below: "tts-selection--below",
                        control: "tts-selection-controller",
                        playing: "tts-selection--playing",
                    },
                    markup: {
                        control: `<x-tts.popup />`
                    }
                },
                distributeOptions: [{
                    source: "{that}.options.strings",
                    target: "{that controller}.options.strings",
                    namespace: "controllerStrings"
                }, {
                    source: "{that}.options.strings",
                    target: "{that selectionReader}.options.strings",
                    namespace: "selectionReaderStrings"
                }]
            });
        </script>
    @endpush
@endif
