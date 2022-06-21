<x-app-wide-layout>
    <x-slot name="title">{{ __('How this works for Individuals with Disabilities and Deaf Individuals') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
        </ol>
        <h1>
            <span class="weight:normal">{{ __('How this works for') }}</span><br />
            {{ __('Individuals with Disabilities and Deaf Individuals') }}
        </h1>
    </x-slot>

    <h2>{{ __('What you can do on this website') }}</h2>
    <p>{{ __('You can choose how you’d like to take part:') }}</p>

    <h3>{{ __('Be a Consultation Participant') }}</h3>
    <p>{{ __('Participate in consultations by organizations and businesses who are working on accessibility projects and get paid for this. Access resources and training on how to do this.') }}</p>
    <p><a href="#TODO">{{ __('Learn more about being a consultation participant') }}</a></p>

    <h3>{{ __('Be an Accessibility Consultant') }}</h3>
    <p>{{ __('Help organizations and businesses design their consultations, and potentially help facilitate these consultations.') }}</p>
    <p><a href="#TODO">{{ __('Learn more about being an accessibility consultant') }}</a></p>

    <h3>{{ __('Be a Community Connector') }}</h3>
    <p>{{ __('Connect members of your community with governments and businesses who are looking for consultation participants. Help them learn how to best work with your community.') }}</p>
    <p><a href="#TODO">{{ __('Learn more about being a community connector') }}</a></p>

    <p class="h3">
        {{ __('Have more questions?') }}<br />
        {{ __('Call our support line at :number', ['number' => settings()->get('phone', '1-800-123-4567')]) }}
    </p>

    @guest
        <h2>{{ __('Join our accessibility community') }}</h2>
        <h3>{{ __('Sign up online') }}</h3>
        <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>

        <h3>{{ __('Sign up on the phone') }}</h3>
        <p>{{ __('Call our support line at :number', ['number' => settings()->get('phone', '1-800-123-4567')]) }}</p>
        <p><a href="#TODO">{{ __('Find a local community organization to help me sign up') }}</a></p>
    @endguest
</x-app-wide-layout>
