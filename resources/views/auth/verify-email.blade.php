<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Verify your email') }}
        </x-slot>

        <div>
            {{ __('Please verify your email address by clicking on the link we emailed to you. If you didn’t receive the email, we will gladly send you another.') }}
        </div>

        <div class="stack">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <button>
                        {{ __('Resend verification email') }}
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ localized_route('logout') }}">
                @csrf

                <button type="submit">
                    {{ __('Sign out') }}
                </button>
            </form>
        </div>
    </x-auth-card>
</x-guest-layout>
