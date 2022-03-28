@if($errors->getBags())
    <div x-data="{ready: false}" x-init="ready = true" role="alert">
        <template x-if="ready">
            <div>
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
            </div>
        </template>
    </div>
@endif
