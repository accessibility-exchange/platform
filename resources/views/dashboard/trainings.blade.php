<x-app-layout page-width="wide">
    <x-slot name="header">
        <a href="{{ localized_route('dashboard') }}">{{ __('Dashboard') . ' >' }}</a>
    </x-slot>
    <h1>{{ __('My trainings') }}</h1>
    <div>
        <h2>{{ __('In progress') }}</h2>
        <div class="mt-6 grid">
            @if ($inProgressCourses)
                @foreach ($inProgressCourses as $course)
                    <div class="card">
                        <div>
                            <h3><a href="{{ localized_route('courses.show', $course) }}">{{ $course->title }}</a></h3>
                        </div>
                        <div>{{ __('Training by') }}</div>
                        <div>{{ __('Language:') . ' ' . locale() }}</div>
                        <span class="badge">{{ __('In progress') }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        <h2>{{ __('Completed') }}</h2>
        <div class="mt-6 grid">
            @foreach ($completedCourses as $course)
                <div>
                    <div class="card">
                        <div>
                            <h3><a href="{{ localized_route('courses.show', $course) }}">{{ $course->title }}</a></h3>
                        </div>
                        <div>{{ __('Training by') }}</div>
                        <div>{{ __('Language:') . ' ' . locale() }}</div>
                        <span class="badge">{{ __('Completed') }}</span>
                    </div>
                    <div class="mt-6 mb-4">
                        @livewire('email-results', ['quiz' => $course->quiz])
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
