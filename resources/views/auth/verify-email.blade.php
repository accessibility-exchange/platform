<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <div>
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div>
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button>
                        {{ __('Resend Verification Email') }}
                    </x-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </x-auth-card>
</x-guest-layout>
