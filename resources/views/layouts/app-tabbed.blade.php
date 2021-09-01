<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" data-theme="{{ Cookie::get('theme', 'system') }}">
    <head>
        @include('partials.head')
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
        <main>
            <article class="flow tab-wrapper" itemscope itemtype="https://schema.org/{{ $itemtype ?? 'WebPage' }}">
                <!-- Page Heading -->
                <header class="flow">
                    {{ $header }}
                </header>

                <!-- Flash Messages -->
                @include('partials.flash-messages')

                <!-- Page Content -->
                <div class="content flow">
                    {{ $slot }}
                </div>
            </article>
        </main>

        @include('layouts.footer')
    </body>
</html>
