        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? __('app.name') }} &mdash; {{ __('app.name') }}</title>
        <meta name="description" content="{{ __('app.description') }}">
        <meta name="theme-color" content="#fff" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#000" media="(prefers-color-scheme: dark)">

        <!-- Manifest -->
        <link href="{{ asset('/manifest.webmanifest') }}" rel="manifest" crossorigin="use-credentials">

        <!-- Icons -->
        <link href="{{ asset('/favicon.ico') }}" rel="icon">
        <link type="image/svg+xml" href="{{ asset('/icon.svg') }}" rel="icon">
        <link href="{{ asset('/apple-touch-icon.png') }}" rel="apple-touch-icon">

        <!-- Styles -->
        @vite('resources/css/app.css')
        @googlefonts
        @livewireStyles()

        <!-- Scripts -->
        <script>
            document.documentElement.className = document.documentElement.className.replace("no-js", "js");
        </script>
        @if (Cookie::get('theme', 'system') === 'system')
            <script>
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.dataset.theme = 'dark';
                } else {
                    document.documentElement.dataset.theme = 'light';
                }
            </script>
        @endif
        @vite('resources/js/app.js')
