<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Reset your password') }}
        </x-slot>

        <div>
            {{ __('hearth::auth.forgot_intro') }}
        </div>

        <form class="stack" method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <!-- Email Address -->
            <div class="field @error('email') field--error @enderror">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                <x-hearth-input name="email" type="email" :value="old('email')" required autofocus />
                <x-hearth-error for="email" />
            </div>

            <button>
                {{ __('hearth::auth.forgot_submit') }}
            </button>
        </form>
    </x-auth-card>
</x-guest-layout>
