@guest
    <x-section class="full accent" aria-labelledby="join">
        <div class="center center:wide stack stack:xl">
            <h2 class="text-center" id="join">{{ __('Join our accessibility community') }}</h2>
            <x-interpretation name="{{ __('Join our accessibility community', [], 'en') }}"
                namespace="{{ isset($withPricing) ? 'join-with_pricing' : 'join' }}" />
            <div class="grid">
                <div class="stack">
                    <h3>{{ __('Sign up online') }}</h3>
                    <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                </div>
                <div class="stack">
                    <h3>{{ __('Sign up on the phone') }}</h3>
                    <p>{{ __('Call our support line at :number', ['number' => phone(settings('phone'), 'CA')->formatForCountry('CA')]) }}
                    </p>
                </div>
                @if ($withPricing ?? false)
                    <div class="stack">
                        <h3 class="h4">{{ __('Learn about our pricing') }}</h3>
                        <p><a class="cta" href="{{ localized_route('about.pricing') }}">{{ __('Go to pricing') }}</a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </x-section>
@endguest
