<x-app-layout body-class="course" page-width="wide">
    <x-slot name="title">{{ $course->title }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('resource-collections.index') }}">{{ __('Resources') }}</a></li>
        </ol>
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
        <x-interpretation name="{{ __('Course', [], 'en') }}" />
    </x-slot>
    <div class="stack ml-2 mr-2">
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
        <x-interpretation name="{{ __('About this course', [], 'en') }}" />
        @if ($course->video)
            <div class="stack w-full" x-data="vimeoPlayer({
                url: @js($course->video),
                byline: false,
                dnt: true,
                pip: true,
                portrait: false,
                responsive: true,
                speed: true,
                title: false
            })" @ended="player().setCurrentTime(0)">
            </div>
        @endif
        @if ($course->introduction)
            {!! Str::markdown($course->introduction, config('markdown')) !!}
        @endif
    </div>
    <div class="border-divider mb-12 mt-14 border-x-0 border-b-0 border-t-3 border-solid pt-6">
        <h2>{{ __('Modules') }}</h2>
        <x-interpretation name="{{ __('Modules', [], 'en') }}" />
        <div class="grid md:grid-cols-2">
            @foreach ($modules as $module)
                <div class="card">
                    <div class="flex items-start justify-between gap-8">
                        <h3>
                            <a
                                href="{{ localized_route('modules.module-content', ['course' => $course, 'module' => $module]) }}">
                                {{ $module->title }}
                            </a>
                        </h3>
                        @if ($user->modules->find($module->id)?->getRelationValue('pivot')->finished_content_at)
                            <span class="badge shrink-0">{{ __('completed') }}</span>
                        @elseif ($user->modules->find($module->id)?->getRelationValue('pivot')->started_content_at)
                            <span class="badge shrink-0">{{ __('In progress') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1">
                        <x-heroicon-o-play />
                        <p>{{ __('Video') }}</p>
                    </div>
                    <p>{{ $module->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @if (!$receivedCertificate)
        <div class="border-divider mb-12 mt-14 border-x-0 border-b-0 border-t-3 border-solid pt-6">
            <h2>{{ __('Quiz') }}</h2>
            <x-interpretation name="{{ __('Quiz', [], 'en') }}" />
            @if ($finishedCourse && !$receivedCertificate && $hasQuiz)
                <a class="cta" href="{{ localized_route('quizzes.show', $course) }}">{{ __('Take Quiz') }}</a>
            @elseif(!$finishedCourse && !$receivedCertificate && $hasQuiz)
                <p class="mb-6">
                    {{ __('Once you are done watching the videos for all the modules, you can take this quiz. Upon passing this quiz, you can receive your certificate of completion.') }}
                <p>
                    <button type="button" @ariaDisabled>{{ __('Take Quiz') }}</button>
                @elseif($receivedCertificate)
            @endif
        </div>
    @endif
</x-app-layout>
