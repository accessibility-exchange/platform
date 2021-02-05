<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Accessibility in Action') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        @include('layouts.banner')

        <!-- Main Content -->
        <main>
            <article class="wrapper flow" itemscope itemtype="https://schema.org/{{ $itemtype ?? 'WebPage' }}">
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
    </body>
</html>
