<x-app-wide-layout>
    <x-slot name="header">
    </x-slot>
    <div class="stack ml-2 mr-2">
        <a href="{{ localized_route('courses.show', $course_id) }}">{{ __('Back') }} > {{ $course_title }}</a>
        <x-slot name="title">{{ $title }}</x-slot>
    </div>
    <div class="stack ml-2 mr-2">
        <h2>{{ $title }}</h2>
        <video class="w-full" controls>
            <source src="" type="video/mp4">
        </video>
        <p>{{ $introduction }}</p>
    </div>
    @if ($quiz)
        <div>
            <a href="{{ localized_route('quizzes.show', $quiz) }}">
                {{ __('Take Quiz') }}
            </a>
        </div>
    @endif
</x-app-wide-layout>
