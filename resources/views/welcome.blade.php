<!DOCTYPE html>
<html class="no-js" data-theme="{{ Cookie::get('theme', 'system') }}"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
                <header class="dark full">
                    <div class="center center:wide stack stack:lg">
                        <h1 itemprop="name">{{ __('The Accessibility Exchange') }}</h1>
                        <p class="h4">
                            {{ __('Connecting the disability and Deaf communities and their supporters with ') }}<br />{{ __('organizations and businesses to work on accessibility projects together.') }}
                        </p>
                        @guest
                            <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                        @endguest
                    </div>
                </header>
                <!-- Page Content -->
                <div class="content stack">
                    <section class="stack" aria-labelledby="what">
                        <h2 class="align:center" id="what">{{ __('What is the Accessibility Exchange?') }}</h2>
                        <div class="grid">
                            <div class="box border--lavender">
                                <h3>{{ __('Connects the disability and Deaf communities with regulated organizations') }}
                                </h3>
                                <p>{{ __('Connects the disability and Deaf communities and supporters with organizations that are “regulated” or supervised and monitored by the federal government, so that together they can work on accessibility projects, as required by the Accessible Canada Act.') }}
                                </p>
                            </div>
                            <div class="box border-t-magenta-3">
                                <h3>{{ __('Provides Guidance and Resources') }}</h3>
                                <p>{{ __('Provides valuable resources and guides for organizations and people with disabilities and Deaf people on how to engage in accessible and inclusive ways.') }}
                                </p>
                            </div>
                        </div>
                        <div class="frame">
                            <x-placeholder />
                        </div>
                    </section>

                    <section class="stack stack:lg" aria-labelledby="how">
                        <h2 class="align:center" id="how">{{ __('How does this work?') }}</h2>

                        <p>{{ __('This site is for three kinds of users. Select an option below to learn more.') }}</p>
                        <x-media-text mediaWidth="md:w-2/3" textWidth="md:w-1/3">
                            <x-slot name="media">
                                <x-placeholder width="915" height="515" />
                            </x-slot>
                            <div class="flex h-full flex-col justify-center">
                                <h3><a
                                        href="{{ localized_route('about.for-individuals') }}">{{ __('For Individuals') }}</a>
                                </h3>
                                <p>{{ __('This is for individuals with disabilities or Deaf people and their supporters, and those wishing to offer accessibility consulting and community connection services.') }}
                                </p>
                            </div>
                        </x-media-text>
                        <x-media-text mediaWidth="md:w-2/3" textWidth="md:w-1/3">
                            <x-slot name="media">
                                <x-placeholder width="915" height="515" />
                            </x-slot>
                            <div class="flex h-full flex-col justify-center">
                                <h3><a
                                        href="{{ localized_route('about.for-regulated-organizations') }}">{{ __('For Federally Regulated Organizations') }}</a>
                                </h3>
                                <p>{{ __('This is for federal departments, agencies, and crown corporations, other public sector bodies and businesses.') }}
                                </p>
                            </div>
                        </x-media-text>
                        <x-media-text mediaWidth="md:w-2/3" textWidth="md:w-1/3">
                            <x-slot name="media">
                                <x-placeholder width="915" height="515" />
                            </x-slot>
                            <div class="flex h-full flex-col justify-center">
                                <h3><a
                                        href="{{ localized_route('about.for-community-organizations') }}">{{ __('For Community Organizations') }}</a>
                                </h3>
                                <p>{{ __('This includes disability and Deaf representative organizations, support organizations, and other civil society organizations (not only disability focused).') }}
                                </p>
                            </div>
                        </x-media-text>
                    </section>

                    <section class="dark full" aria-labelledby="disability">
                        <div class="center center:wide stack stack:xl">
                            <div class="with-sidebar with-sidebar:2/3">
                                <div>
                                    <h2 id="disability">{{ __('What do we mean when we say “disability”?') }}</h2>
                                </div>
                                <div class="stack">
                                    <p class="h4">
                                        {{ __('Disability is not in the person. It results when a person’s long-term physical, mental health, developmental, or sensory characteristics differ from society’s norms. When buildings, services, and workplaces are designed for the norm, they often present barriers to a person’s full and equal participation in society. That’s what we mean by disability. ') }}
                                    </p>
                                    {{-- TODO: add link to glossary definition --}}
                                    {{-- <p><a class="font-medium" href="">{{ __('Learn more about disability') }}</a></p> --}}
                                </div>
                            </div>
                            <div class="frame">
                                <x-placeholder />
                            </div>
                        </div>
                    </section>
                    <section class="stack align:center" aria-labelledby="partnership">
                        <h2 id="partnership">{{ __('Developed in partnership') }}</h2>
                        <p class="mx-auto max-w-prose">
                            {{ __('This website was made in partnership with members and organizations from the disability and Deaf communities, supporters, and members from Federally Regulated Organizations.') }}
                        </p>
                    </section>

                    @guest
                        <section class="full accent" aria-labelledby="join">
                            <div class="center center:wide stack stack:xl align:center">
                                <h2 id="join">{{ __('Join our accessibility community') }}</h2>
                                <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                            </div>
                        </section>
                    @endguest
                </div>
            </article>
        </div>
    </main>

    @include('layouts.footer')
</body>

</html>
