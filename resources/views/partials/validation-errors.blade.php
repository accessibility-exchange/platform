@if($errors->getBags())
    @foreach($errors->getBags() as $bag)
        <x-alert type="error" :title="__('forms.errors_found')">
            <p>{{ __('forms.errors_found_message') }}</p>
            <ul>
            @foreach ($bag->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </x-alert>
    @endforeach
@endif
