<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
    <head>
        @include('partials.head')
    </head>
    <body>
        @include('layouts.banner')

        <!-- Main Content -->
        <main>
            <article class="wrapper flow">
                <!-- Page Heading -->
                <header class="flow">
                    <h1>{{ config('app.name', 'Accessibility in Action') }}</h1>
                </header>

                <!-- Page Content -->
                <div class="content flow">
                    <p>{{ __('Welcome!') }}</p>
                </div>
            </article>
        </main>
    </body>
</html>
