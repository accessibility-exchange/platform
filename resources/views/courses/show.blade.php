<x-app-wide-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $title }}
        </h1>
    </x-slot>
    <div class="stack ml-2 mr-2">
        <a href="{{ localized_route('courses.index') }}">{{ __('Back') }}</a>
        <x-slot name="title">{{ $title }}</x-slot>
        <div class="flex justify-between">
            <div>
                {{ __('Author') }}
            </div>
            <div>
                {{ __('Resource type') }}
            </div>
            <div>
                {{ __('Published on') }}
            </div>
        </div>
    </div>
    <div class="stack ml-2 mr-2">
        <h2>{{ __('About this course') }}</h2>
        @if ($video)
            <video class="w-full" controls>
                <source src="" type="video/mp4">
            </video>
        @endif
        <p>{{ $introduction }}</p>
    </div>
    <div class="stack ml-2 mr-2">
        <h2>{{ __('Modules') }}</h2>
        <div class="grid">
            @foreach ($modules as $module)
                <div class="flex flex-col">
                    <video controls>
                        <source src="{{ $module }}" type="video/mp4">
                    </video>
                    <a href="{{ localized_route('modules.show', $module) }}">
                        {{ $module->title }}
                    </a>
                    <p>{{ __('Video') }}</p>
                    <p>{{ $module->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-wide-layout>
