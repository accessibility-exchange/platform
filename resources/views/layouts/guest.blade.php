<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" data-theme="{{ Cookie::get('theme', 'light') }}">
    <head>
        @include('partials.head', ['title' => $title ?? __('app.name')])
    </head>
    <body class="guest">
        <main>
            <div class="cover">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
