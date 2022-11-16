<!DOCTYPE html>
<html class="no-js" data-theme="@theme()" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => $title ?? __('app.name')])
</head>

<body>
    @include('layouts.banner')

    <!-- Main Content -->
    <main id="main">
        <div class="center center:wide">
            <article class="stack" itemscope itemtype="https://schema.org/{{ $itemtype ?? 'WebPage' }}">
                <!-- Flash Messages -->
                @include('partials.flash-messages')

                <!-- Page Heading -->
                <header class="stack">
                    {{ $header }}
                </header>

                <!-- Page Content -->
                <div class="content stack">
                    {{ $slot }}
                </div>
            </article>
        </div>
        <x-back-to-top width="wide" />
    </main>

    @include('layouts.footer')
</body>

</html>
