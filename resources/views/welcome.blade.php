<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" data-theme="{{ Cookie::get('theme', 'system') }}">
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
                    <p>{{ __('welcome.intro') }}</p>
                    <p>{!! __('welcome.details', ['link' => '<a href="https://accessibility-in-action.inclusivedesign.ca/" rel="external">' . __('welcome.codesign_site') . '</a>']) !!}</p>
                </div>
            </article>
        </main>
    </body>
</html>
