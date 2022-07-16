<x-app-layout>
    <x-slot name="title">{{ __('Payment information') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Payment information') }}
        </h1>
    </x-slot>

    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('settings.update-payment-information') }}" method="post">
        @csrf
        @method('put')

        <fieldset class="field @error('payment_types') field--error @enderror" x-data="{other: {{ old('other', !is_null($individual->other_payment_type) && $individual->other_payment_type !== '' ? 'true' : 'false') }}}">
            <legend>{{ __('What types of payment are you able to accept?') }}</legend>
            <p class="field__hint">{{ __('Please check all that apply.') }}</p>

            <x-hearth-checkboxes name="payment_types" :options="$paymentTypes" :checked="old('payment_types', $individual->paymentTypes->pluck('id')->toArray())" />
            <div class="field @error('payment_types') field--error @enderror">
                <x-hearth-checkbox name="other" :checked="old('other', !is_null($individual->other_payment_type) && $individual->other_payment_type !== '' || 1)" x-model="other" /> <x-hearth-label for='other'>{{ __('Other (please specify)') }}</x-hearth-label>
            </div>
            <div class="field__subfield @error('other_payment_type') field--error @enderror stack" x-show="other" x-cloak>
                <x-hearth-label for="other_payment_type">{{ __('Payment type') }}</x-hearth-label>
                <x-hearth-input name="other_payment_type" :value="old('other_payment_type', $individual->other_payment_type)" :aria-invalid="$errors->has('payment_types')" />
            </div>
        </fieldset>

        <button>{{ __('Save') }}</button>
    </form>

</x-app-layout>
