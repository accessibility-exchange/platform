<!DOCTYPE html>
<html class="no-js" lang="en-CA">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('app.name') }}</title>
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

    <!-- Scripts -->
    <script>
        document.documentElement.className = document.documentElement.className.replace("no-js", "js");
    </script>

    <script>
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.dataset.theme = '{{ App\Enums\Theme::Dark->value }}';
        } else {
            document.documentElement.dataset.theme = '{{ App\Enums\Theme::Light->value }}';
        }
    </script>
</head>

<body>
    <!-- Main Content -->
    <main class="flex min-h-screen justify-center" id="main">
        <div class="center px-6">
            <article class="stack">

                <!-- Page Heading -->
                <header class="text-center">
                    @svg('tae-logo-mono', ['class' => 'maintenance__logo'])
                    <h1 class="text-3xl">{{ __('Maintenance', [], 'en') }} / <span
                            lang="fr">{{ __('Maintenance', [], 'fr') }}</span></h1>
                </header>

                <!-- Page Content -->
                <div class="content stack text-center">

                    <p>{{ __('The site is currently undergoing maintenance.', [], 'en') }}<br />
                        {{ __('Please wait a few moments and try again.', [], 'en') }}
                    </p>
                    <p lang="fr">
                        {{ __('The site is currently undergoing maintenance.', [], 'fr') }}<br />
                        {{ __('Please wait a few moments and try again.', [], 'fr') }}
                    </p>
                </div>
            </article>
        </div>
    </main>
</body>

</html>
