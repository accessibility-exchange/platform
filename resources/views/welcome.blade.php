<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" data-theme="{{ Cookie::get('theme', 'light') }}">
<head>
    @include('partials.head', ['title' => $title ?? __('app.name')])
</head>
<body class="welcome">
@include('layouts.banner')

<!-- Main Content -->
<main id="main">
    <div class="center center:wide">
        <article class="stack" itemscope itemtype="https://schema.org/{{ $itemtype ?? 'WebPage' }}">
            <!-- Flash Messages -->
        @include('partials.flash-messages')
        <!-- Page Heading -->
            <header class="dark stack">
                <h1 itemprop="name">{{ __('The Accessibility Exchange') }}</h1>
                <p class="h4">{{ __('Connecting the disability and Deaf communities and supporters with') }}<br />{{ __('organizations and businesses to work on accessibility projects together.') }}</p>
                @guest
                    <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                @endguest
            </header>
            <!-- Page Content -->
            <div class="content stack">
                <section aria-labelledby="what" class="stack">
                    <h2 id="what" class="align:center">{{ __('What is the Accessibility Exchange?') }}</h2>
                    <div class="grid">
                        <div class="box">
                            <h3>{{ __('Connects the Disability Community to Organizations') }}</h3>
                            <p>{{ __('Connects disability communities and supporters to organizations that are “regulated” or supervised and monitored by the federal government, to work on accessibility projects, as required by the Accessible Canada Act.') }}</p>
                        </div>
                        <div class="box">
                            <h3>{{ __('Provides Guidance and Resources') }}</h3>
                            <p>{{ __('Provides valuable resources and guides for organizations and people with disabilities and Deaf people on how to engage in accessible and inclusive ways.') }}</p>
                        </div>
                    </div>
                    <div class="frame">
                        <x-placeholder fill="var(--color-blue-6)" />
                    </div>
                </section>

                <section aria-labelledby="how" class="stack">
                    <h2 id="how" class="align:center">{{ __('How does this work?') }}</h2>
                    <div class="with-sidebar">
                        <div class="frame">
                            <x-placeholder width="915" height="515" fill="var(--color-blue-6)" />
                        </div>
                        <div class="stack center:vertical">
                            <h3><a href="{{ localized_route('about.for-individuals') }}">{{ __('For individuals with Disabilities or Deaf Individuals') }}</a></h3>
                        </div>
                    </div>
                    <div class="with-sidebar">
                        <div class="frame">
                            <x-placeholder width="915" height="515" fill="var(--color-blue-6)" />
                        </div>
                        <div class="stack center:vertical">
                            <h3><a href="{{ localized_route('about.for-regulated-organizations') }}">{{ __('For Governments, Businesses, and other Non-Profits') }}</a></h3>
                            <p>{{ __('This is for Federally Regulated Organizations under the Accessible Canada Act.') }}</p>
                        </div>
                    </div>
                    <div class="with-sidebar">
                        <div class="frame">
                            <x-placeholder width="915" height="515" fill="var(--color-blue-6)" />
                        </div>
                        <div class="stack center:vertical">
                            <h3><a href="{{ localized_route('about.for-community-organizations') }}">{{ __('For Community Organizations') }}</a></h3>
                            <p>{{ __('This includes disability and Deaf representative organizations, support organizations, and other civil societies (not only disability focused).') }}</p>
                        </div>
                    </div>
                </section>

                <section aria-labelledby="disability" class="stack dark full bg-blue-7 flex flex-wrap items-center gap-16">
                    <h2 id="disability" class="basis-1/3 grow-0">{{ __('What do we mean when we say “disability”?') }}</h2>
                    <div class="basis-3/5 grow stack">
                        <p class="h4">{{ __('Disability is not in the person. It results when a person’s long-term physical, mental health, developmental, or sensory characteristics differ from society’s norms. When buildings, services, and workplaces are designed for the norm, they often present barriers to a person’s full and equal participation in society. That’s what we mean by disability. ') }}</p>
                        <p><a class="weight:normal" href="#TODO">{{ __('Learn more about disability') }}</a></p>
                    </div>
                    <div class="frame w-100">
                        <x-placeholder fill="var(--color-blue-6)" />
                    </div>
                </section>
                <h2>{{ __('Developed in partnership') }}</h2>
                <p>{{ __('This website was made in partnership with members and organizations from the disability and Deaf communities, supporters, and members from Federally Regulated Organizations.') }}</p>
                @guest
                    <h2>{{ __('Join our Accessibility Community') }}</h2>
                    <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                @endguest
            </div>
        </article>
    </div>
</main>

@include('layouts.footer')
</body>
</html>
