<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Payment Disclaimer') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Payment Disclaimer') }}
        </h1>
        <x-interpretation name="{{ __('Payment Disclaimer', [], 'en') }}" />
    </x-slot>

    <p>{{ __('Please note, each organization has a different way of paying, which depends on their accounting system or practices. The different types of payment might be by cheque, e-transfer, or direct deposit. Organizations that pay via direct deposit — that means the money would be deposited directly into your bank — will require you to provide your banking information so that they can process the payment. Sometimes all they need is a void cheque.') }}
    </p>
    <p>{{ __('Please note that most organizations have a payment cycle of 30 business days, so payment for participating in a consultation will likely not be processed immediately.') }}
    </p>

    <div>
        <form class="width:full" action="{{ localized_route('individuals.update-payment-disclaimer-status') }}"
            method="post">
            @method('put')
            @csrf

            <input name="viewed_payment_disclaimer" type="hidden" value="1" />
            <button>{{ __('Continue') }}</button>
        </form>
    </div>
</x-app-layout>
