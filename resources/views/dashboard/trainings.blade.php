<x-app-layout page-width="wide">
    <x-slot name="header">
        <a href="{{ localized_route('dashboard') }}">{{ __('Dashboard') . ' >' }}</a>
    </x-slot>
    <h1>{{ __('My trainings') }}</h1>
    <x-interpretation name="{{ __('My trainings', [], 'en') }}" />
    <div>
        <h2>{{ __('In progress') }}</h2>
        <x-interpretation name="{{ __('In progress', [], 'en') }}" />
        <div class="mt-6 grid">
            @if ($inProgressCourses)
                @foreach ($inProgressCourses as $course)
                    <div class="card">
                        <div>
                            <h3><a href="{{ localized_route('courses.show', $course) }}">{{ $course->title }}</a></h3>
                        </div>
                        <div>
                            {{ __('Training by: :author', ['author' => $course->author ?? $course->organization?->name]) }}
                        </div>
                        <div>{{ __('Language: :locale', ['locale' => locale()]) }}</div>
                        <span class="badge">{{ __('In progress') }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="border-divider mb-12 mt-14 border-x-0 border-b-0 border-t-3 border-solid pt-6">
        <h2>{{ __('Completed') }}</h2>
        <x-interpretation name="{{ __('Completed', [], 'en') }}" />
        <div class="mt-6 grid">
            @foreach ($completedCourses as $course)
                <div>
                    <div class="card">
                        <div>
                            <h3><a href="{{ localized_route('courses.show', $course) }}">{{ $course->title }}</a></h3>
                        </div>
                        <div>
                            {{ __('Training by: :author', ['author' => $course->author ?? $course->organization?->name]) }}
                        </div>
                        <div>{{ __('Language: :locale', ['locale' => locale()]) }}</div>
                        <span class="badge">{{ __('Completed') }}</span>
                    </div>
                    <div class="mb-4 mt-6">
                        @livewire('email-results', ['quiz' => $course->quiz])
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
