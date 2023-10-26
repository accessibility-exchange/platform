@if ($errors->getBags())
    <x-live-region>
        @foreach ($errors->getBags() as $bag)
            <x-hearth-alert type="error" :title="__('hearth::forms.errors_found')">
                <p>{{ __('hearth::forms.errors_found_message') }}</p>
                <x-interpretation name="{{ __('hearth::forms.errors_found_message', [], 'en') }}"
                    namespace="errors_found_message" />
                <ul>
                    @foreach ($bag->unique() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-hearth-alert>
        @endforeach
    </x-live-region>
@endif
