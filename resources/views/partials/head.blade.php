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
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script>document.documentElement.className = document.documentElement.className.replace("no-js", "js");</script>
        <script src="{{ mix('js/app.js') }}" defer></script>
