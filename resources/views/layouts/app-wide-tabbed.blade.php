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
                    <!-- Page Heading -->
                    <header class="full bg-white -mt-12 py-12 border-b-grey-3 border-solid border-b border-x-0 border-t-0">

                        <div class="center center:wide stack">
                            <!-- Flash Messages -->
                            @include('partials.flash-messages')

                            {{ $header }}
                        </div>
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
