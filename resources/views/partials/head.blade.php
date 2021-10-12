        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? __('app.name') }} &mdash; {{ __('app.name') }}</title>
        <meta name="description" content="{{ __('app.description') }}">
        <meta name="theme-color" content="#fff" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#000" media="(prefers-color-scheme: dark)">

        <!-- Manifest -->
        <link rel="manifest" href="/manifest.webmanifest">

        <!-- Icons -->
        <link rel="icon" href="/favicon.ico">
        <link rel="icon" href="/icon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Styles -->
        @env('production')
        <link href="{{ mix('css/app.css') }}" rel="stylesheet" integrity="{{ Sri::hash('css/app.css') }}" crossorigin="anonymous" />
        @else
        <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
        @endenv
        @googlefonts
        @livewireStyles()

        <!-- Scripts -->
        <script>document.documentElement.className = document.documentElement.className.replace("no-js", "js");</script>
        @env('production')
        <script src="{{ mix('js/manifest.js') }}" integrity="{{ Sri::hash('js/manifest.js') }}" crossorigin="anonymous" defer></script>
        <script src="{{ mix('js/vendor.js') }}" integrity="{{ Sri::hash('js/vendor.js') }}" crossorigin="anonymous" defer></script>
        <script src="{{ mix('js/app.js') }}" integrity="{{ Sri::hash('js/app.js') }}" crossorigin="anonymous" defer></script>
        @else
        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vendor.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
        @endenv

