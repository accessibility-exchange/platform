<x-app-layout page-width="wide">
    <x-slot name="title">{{ $course->title }}</x-slot>
    <x-slot name="header">
        @if ($receivedCertificate)
            <div class="notification flex justify-between">
                <div class="my-auto">
                    {{ __('You now have completed this course.') }}
                </div>
                @livewire('email-results', ['quiz' => $course->quiz])
            </div>
        @endif
        <h1>
            {{ $course->title }}
        </h1>
    </x-slot>
    <div class="stack ml-2 mr-2">
        <a href="{{ localized_route('resource-collections.index') }}">{{ __('Back') }}</a>
        <x-slot name="title">{{ $course->title }}</x-slot>
        <div class="flex justify-between">
            <div>
                {{ __('Author: :author', ['author' => $course->author ?? $course->organization?->name]) }}
            </div>
            <div>
                {{ __('Resource type: Training') }}
            </div>
            <div>
                {{ __('Published on: :created_at', ['created_at' => $course->created_at->isoFormat('LL')]) }}
            </div>
        </div>
    </div>
    <div class="stack ml-2 mr-2">
        <h2>{{ __('About this course') }}</h2>
        @if ($course->video)
            <div class="stack w-full" x-data="vimeoPlayer({
                url: '{{ $course->video[locale()] }}',
                byline: false,
                pip: true,
                portrait: false,
                responsive: true,
                speed: true,
                title: false
            })" @ended="player().setCurrentTime(0)">
            </div>
        @endif
        <p>{{ $course->introduction }}</p>
    </div>
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        <h2>{{ __('Modules') }}</h2>
        <div class="grid">
            @foreach ($modules as $module)
                <div class="flex flex-col">
                    <div class="card">
                        <div class="flex items-center justify-between">
                            <a
                                href="{{ localized_route('modules.module-content', ['course' => $course, 'module' => $module]) }}">
                                {{ $module->title }}
                            </a>
                            @if ($user->modules->find($module->id)?->getRelationValue('pivot')->finished_content_at)
                                <span class="badge">{{ __('completed') }}</span>
                            @elseif ($user->modules->find($module->id)?->getRelationValue('pivot')->started_content_at)
                                <span class="badge">{{ __('In progress') }}</span>
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
    </div>
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        <h2>{{ __('Quiz') }}</h2>
        @if ($finishedCourse && !$receivedCertificate && $hasQuiz)
            <form class="stack" action="{{ localized_route('quizzes.show', $course) }}" novalidate>
                @csrf
                <button>{{ __('Take Quiz') }}</button>
            </form>
        @elseif(!$finishedCourse && !$receivedCertificate && $hasQuiz)
            <p class="mb-6">
                {{ __('Once you are done watching the videos for all the modules, you can take this quiz. Upon passing this quiz, you can receive your certificate of completion.') }}
            <p>
                <button type="button" @ariaDisabled>{{ __('Take Quiz') }}</button>
            @elseif($receivedCertificate)
        @endif
    </div>
</x-app-layout>
