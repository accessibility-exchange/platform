<x-guest-layout>
    <x-slot name="title">{{ __('Site unavailable') }}</x-slot>

    <div class="stack hero">
        <div class="center max-w-prose">
            <div class="stack text-center">
                <div class="mx-auto w-1/2">
                    <x-tae-logo class="logo" role="presentation" />
                    <x-tae-logo-mono class="logo logo--themeable" role="presentation" />
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
