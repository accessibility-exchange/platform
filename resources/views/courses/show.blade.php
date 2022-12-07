<x-app-wide-layout>
    <x-slot name="title">{{ $course->title }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $course->title }}
        </h1>
    </x-slot>
    <div class="stack ml-2 mr-2">
        <a href="{{ localized_route('courses.index') }}">{{ __('Back') }}</a>
        <x-slot name="title">{{ $course->title }}</x-slot>
        <div class="flex justify-between">
            <div>
                {{ __('Author') }}
            </div>
            <div>
                {{ __('Resource type') . ': ' . __('Training') }}
            </div>
            <div>
                {{ __('Published on') }}
            </div>
        </div>
    </div>
    <div class="stack ml-2 mr-2">
        <h2>{{ __('About this course') }}</h2>
        @if ($course->video)
            <video class="w-full" controls>
                <source src="" type="video/mp4">
            </video>
        @endif
        <p>{{ $course->introduction }}</p>
    </div>
    <div class="stack ml-2 mr-2">
        <h2>{{ __('Modules') }}</h2>
        <div class="grid">
            @foreach ($modules as $module)
                <div class="flex flex-col">
                    <video class="w-full" controls>
                    </video>
                    <div>
                        <div class="flex items-center justify-between">
                            <a href="{{ localized_route('modules.module-content', $module) }}">
                                {{ $module->title }}
                            </a>
                            @if ($user->modules->where('id', $module->id)->first()->pivot->finished_content_at ?? null)
                                <span class="badge">{{ __('completed') }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <x-heroicon-o-play />
                            <p>{{ __('Video') }}</p>
                        </div>
                        <p>{{ $module->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($finishedCourse)
            <form class="stack" action="{{ localized_route('quizzes.show', $course->quiz) }}" novalidate>
                @csrf
                <button>{{ __('Take Quiz') }}</button>
            </form>
        @else
            <button type="button" @ariaDisabled>{{ __('Take Quiz') }}</button>
        @endif
    </div>
</x-app-wide-layout>
