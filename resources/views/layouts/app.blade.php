<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Accessibility in Action') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="https://unpkg.com/@accessibility-in-action/looseleaf@1.0.0-alpha.3/style.min.css">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        <div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header>
                <div class="wrapper">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="wrapper">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
