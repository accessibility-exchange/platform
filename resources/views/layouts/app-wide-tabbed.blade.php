<!DOCTYPE html>
<html class="no-js" data-theme="{{ Cookie::get('theme', 'light') }}"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
                <header class="full -mt-12 border-x-0 border-b border-t-0 border-solid border-b-grey-3 bg-white py-12">

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
