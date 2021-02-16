<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
    <head>
        @include('partials.head')
    </head>
    <body>
        <main>
            <div class="wrapper flow">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
