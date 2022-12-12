<x-app-layout page-width="wide">
    <x-slot name="header">
        <h1>
            {{ __('Available courses') }}
        </h1>
    </x-slot>
    <div class="stack ml-2 mr-2">
        @foreach ($courses as $course)
            <div>
                <a href="{{ localized_route('courses.show', $course) }}">
                    <p>{{ $course->title }}</p>
                </a>
            </div>
        @endforeach
    </div>
</x-app-layout>
