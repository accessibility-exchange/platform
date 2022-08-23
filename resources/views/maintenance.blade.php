<x-guest-layout>
    <x-slot name="title">{{ __('Site unavailable') }}</x-slot>

    <div class="stack hero">
        <div class="center max-w-prose">
            <div class="stack text-center">
                <div class="w-1/2 mx-auto">
                    <x-tae-logo role="presentation" class="logo" />
                    <x-tae-logo-mono role="presentation" class="logo logo--themeable" />
                    <span class="visually-hidden">{{ __('app.name') }}</span>
                </div>

                <h1 class="align:center">{{ __('Site unavailable') }}</h1>
                <p>
                    {{ __('The site is currently undergoing maintenance.') }}<br />
                    {{ __('Please wait a few moments and try again.') }}
                </p>
            </div>
        </div>
    </div>


</x-guest-layout>
