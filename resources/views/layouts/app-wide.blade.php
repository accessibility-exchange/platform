<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" data-theme="{{ Cookie::get('theme', 'light') }}">
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
        </main>

        @include('layouts.footer')
    </body>
</html>
