<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Sign up for this engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('engagements.index') }}">{{ __('Engagements') }}</a></li>
            <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1 class="w-full md:w-2/3">
            {{ __('Sign up for this engagement') }}
        </h1>
        <x-interpretation name="{{ __('Sign up for this engagement', [], 'en') }}" />
    </x-slot>

    <div class="stack mb-12 w-full md:w-2/3">
        <h2>{{ __('Confirm payment method') }}</h2>

        <p>{{ __('Please confirm that you can receive payment in one of the following formats:') }}</p>

        <ul role="list">
            @foreach ($engagement->paymentTypes as $payment_type)
                <li class="py-0">{{ $payment_type->name }}</li>
            @endforeach
            @if ($engagement->other_payment_type)
                <li class="py-0">{{ $engagement->other_payment_type }}</li>
            @endif
        </ul>

        <div class="mt-12">
            <a class="cta" href="{{ localized_route('engagements.sign-up', $engagement) }}">{{ __('Confirm') }}</a>
        </div>
    </div>
</x-app-layout>
