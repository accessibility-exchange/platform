@if($errors->getBags())
    @foreach($errors->getBags() as $bag)
        <x-hearth-alert type="error" :title="__('hearth::forms.errors_found')">
            <p>{{ __('hearth::forms.errors_found_message') }}</p>
            <ul>
            @foreach ($bag->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </x-hearth-alert>
    @endforeach
@endif
