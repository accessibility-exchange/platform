<x-app-layout>
    <x-slot name="class">welcome</x-slot>
    <x-slot name="title">{{ __('The Accessibility Exchange') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('The Accessibility Exchange') }}</h1>
        <p>{{ __('Connecting the disability and Deaf communities and supporters with organizations and businesses to work on accessibility projects together.') }}</p>
        @guest
            <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
        @endguest
    </x-slot>

    <h2>{{ __('What is the Accessibility Exchange?') }}</h2>

    <h3>{{ __('Connects the Disability Community to Organizations') }}</h3>

    <p>{{ __('Connects disability communities and supporters to organizations that are “regulated” or supervised and monitored by the federal government, to work on accessibility projects, as required by the Accessible Canada Act.') }}</p>

    <h3>{{ __('Provides Guidance and Resources') }}</h3>

    <p>{{ __('Provides valuable resources and guides for organizations and people with disabilities and Deaf people on how to engage in accessible and inclusive ways.') }}</p>

    <h2>{{ __('How does this work?') }}</h2>

    <h3><a href="{{ localized_route('about.for-individuals') }}">{{ __('For individuals with Disabilities or Deaf Individuals') }}</a></h3>
    <h3><a href="{{ localized_route('about.for-regulated-organizations') }}">{{ __('For Governments, Businesses, and other Non-Profits') }}</a></h3>
    <p>{{ __('This is for Federally Regulated Organizations under the Accessible Canada Act.') }}</p>
    <h3><a href="{{ localized_route('about.for-community-organizations') }}">{{ __('For Community Organizations') }}</a></h3>
    <p>{{ __('This includes disability and Deaf representative organizations, support organizations, and other civil societies (not only disability focused).') }}</p>

    <h2>{{ __('What do we mean when we say “disability”?') }}</h2>
    <p>{{ __('Disability is not in the person. It results when a person’s long-term physical, mental health, developmental, or sensory characteristics differ from society’s norms. When buildings, services, and workplaces are designed for the norm, they often present barriers to a person’s full and equal participation in society. That’s what we mean by disability. ') }}</p>
    <h2>{{ __('Developed in partnership') }}</h2>
    <p>{{ __('This website was made in partnership with members and organizations from the disability and Deaf communities, supporters, and members from Federally Regulated Organizations.') }}</p>
    @guest
        <h2>{{ __('Join our Accessibility Community') }}</h2>
        <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
    @endguest
</x-app-layout>
