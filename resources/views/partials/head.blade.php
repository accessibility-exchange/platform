        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Accessibility in Action') }}</title>
        <meta name="description" content="Accessibility in Action supports processes where people with disabilities have the power to make sure that policies, programs, and services by federally regulated entities are accessible to them and respect their human rights.">
        <meta name="theme-color" content="#000"/>

        <!-- Manifest -->
        <link rel="manifest" href="/manifest.webmanifest">

        <!-- Icons -->
        <link rel="icon" href="/favicon.ico">
        <link rel="icon" href="/icon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet" integrity="{{ Sri::hash('css/app.css') }}" crossorigin="anonymous" />

        <!-- Scripts -->
        <script>document.documentElement.className = document.documentElement.className.replace("no-js", "js");</script>
        <script src="{{ mix('js/manifest.js') }}" integrity="{{ Sri::hash('js/manifest.js') }}" crossorigin="anonymous" defer></script>
        <script src="{{ mix('js/vendor.js') }}" integrity="{{ Sri::hash('js/vendor.js') }}" crossorigin="anonymous" defer></script>
        <script src="{{ mix('js/app.js') }}" integrity="{{ Sri::hash('js/app.js') }}" crossorigin="anonymous" defer></script>
