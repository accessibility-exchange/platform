<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" data-theme="{{ Cookie::get('theme', 'light') }}">
    <head>
        @include('partials.head', ['title' => $title ?? __('app.name')])
    </head>
    <body>
        @include('layouts.banner')

        @isset($aside)
        <!-- Sidebar -->
        <aside class="flow">
            {{ $aside }}
        </aside>
        @endif

        <!-- Main Content -->
        <main id="main">
            <article class="wrapper flow" itemscope itemtype="https://schema.org/{{ $itemtype ?? 'WebPage' }}">
                <!-- Flash Messages -->
                @include('partials.flash-messages')

                <!-- Page Heading -->
                <header class="flow">
                    {{ $header }}
                </header>

                <!-- Page Content -->
                <div class="content flow">
                    {{ $slot }}
                </div>
            </article>
        </main>

        @include('layouts.footer')
    </body>
</html>
