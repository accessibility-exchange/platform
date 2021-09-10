<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" data-theme="{{ Cookie::get('theme', 'system') }}">
    <head>
        @include('partials.head', ['title' => $title ?? __('app.name')])
    </head>
    <body>
        <main>
            <div class="wrapper flow">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
