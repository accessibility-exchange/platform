<!DOCTYPE html>
<html class="no-js" data-theme="@theme()" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => $title ?? __('app.name')])
</head>

<body class="{{ $bodyClass }}">
    @include('layouts.header')

    <x-language-modal />

    <!-- Main Content -->
    <main id="main">
        <div @class([
            'center',
            'center:medium' => $pageWidth === 'medium',
            'center:wide' => $pageWidth === 'wide',
        ])>
            <article class="stack">
                <!-- Flash Messages -->
                @include('partials.flash-messages')

                <!-- Page Heading -->
                <header class="{{ $headerClass }}">
                    <!-- Text to Speech -->
                    <x-tts.orator />

                    {{ $header }}
                </header>

                <!-- Page Content -->
                <div class="content stack">
                    {{ $slot }}
                </div>
            </article>
        </div>
        <x-back-to-top :width="$pageWidth ?? null" />
    </main>

    @include('layouts.footer')
    @env(['dev', 'local'])
    @include('partials.hubspot')
    @endenv
</body>

</html>
