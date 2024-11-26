@if ($errors->getBags())
    <x-live-region>
        @foreach ($errors->getBags() as $bag)
            <x-hearth-alert type="error" :title="__('forms.errors_found')">
                <p>{{ __('forms.errors_found_message') }}</p>
                <x-interpretation name="forms.errors_found_message" namespace="errors_found_message" />
                <ul>
                    @foreach ($bag->unique() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-hearth-alert>
        @endforeach
    </x-live-region>
@endif
