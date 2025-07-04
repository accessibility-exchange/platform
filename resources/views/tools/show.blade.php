<x-app-layout body-class="page tool" page-width="wide" header-class="full header--tool">
    <x-slot name="title">{{ $tool->title }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide welcome pb-32">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('tools.index') }}">{{ __('Tools') }}</a></li>
            </ol>
            <h1>
                {{ $tool->title }}
            </h1>
            <x-interpretation name="{{ __('Tool', [], 'en') }}" />

            @if ($tool->description)
                {!! Str::markdown($tool->description, config('markdown')) !!}
            @endif
        </div>
    </x-slot>

    <div class="center center:wide pb-28">
        <div class="stack md:w-2/3">
            @if (empty($content) || $content->isEmpty())
                <p>{{ __('Coming soon') }}</p>
            @else
                {{ $content }}
            @endif
        </div>
    </div>
    @if ($tool->documents->count())
        <x-section class="full dark -mb-8" aria-labelledby="download-tool">
            <div class="center stack stack:xl py-20" x-data="{
                open: false,
                email: @auth() '{{ Auth::user()->email }}' @else false @endauth
            }">
                <h2 class="text-center" id="download-tool">{{ __('Download tool') }}</h2>
                <x-interpretation name="{{ __('Download tool', [], 'en') }}" />
                <div class="field" x-show="email === false">
                    <x-hearth-label for="email" :value="__('Your email (optional)')" />
                    <x-hearth-hint
                        for="email">{{ __('[Copy about why you’re collecting their email]') }}</x-hearth-hint>
                    <x-hearth-input name="email" type="email" x-ref="email" hinted />
                    <button class="w-full" type="button"
                        @click="email = $refs.email.value ? $refs.email.value : null">{{ __('Get download links') }}</button>
                </div>
                <div class="stack stack:xl" x-show="email !== false">
                    <p class="text-center">{{ __('Questions about the tool? Please contact [email here].') }}</p>
                    <p class="text-center"><strong>{{ __('Your download links are below:') }}</strong></p>
                    <ul role="list">
                        @foreach ($tool->revisions->sortBy('date')->reverse()->groupBy('date')->first() as $revision)
                            <li class="row flex items-center justify-between border-x-0 border-b border-t-0 border-solid py-3"
                                style="border-block-end-color: var(--interactive);">
                                <span>{{ $revision->document->name }}
                                    {{ $revision->date->format('Y-m-d') }}</span>
                                <span class="row flex items-center gap-3">
                                    @foreach ($revision->getTranslations('file') as $lang => $file)
                                        <form method="post"
                                            action="{{ localized_route('download', ['revision' => $revision->id], $lang) }}">
                                            @csrf
                                            <input name="email" type="hidden" x-bind:value="email" />
                                            <button
                                                type="submit">{{ __('Download (:lang)', ['lang' => Str::upper($lang)]) }}</button>
                                        </form>
                                    @endforeach
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($tool->revisions->groupBy('date')->count() > 1)
                        <div>
                            <button class="borderless" @click="open = ! open"
                                x-bind:aria-expanded="open.toString()">{{ __('Previous versions') }}
                                @svg('heroicon-o-chevron-down', 'indicator')</button>
                            <x-interpretation name="{{ __('Previous versions', [], 'en') }}" />
                            <div class="pt-8" x-show="open">
                                @foreach ($tool->revisions->sortBy('date')->reverse()->groupBy('date')->slice(1) as $date => $revisions)
                                    <h3>{{ (new Illuminate\Support\Carbon($date))->format('F j, Y') }}</h3>
                                    <ul role="list">
                                        @foreach ($revisions as $revision)
                                            <li class="row flex items-center justify-between border-x-0 border-b border-t-0 border-solid py-3"
                                                style="border-block-end-color: var(--interactive);">
                                                <span>{{ $revision->document->name }}
                                                    {{ $revision->date->format('Y-m-d') }}</span>
                                                <span class="row flex items-center gap-3">
                                                    @foreach ($revision->getTranslations('file') as $lang => $file)
                                                        <form method="post"
                                                            action="{{ localized_route('download', ['revision' => $revision->id], $lang) }}">
                                                            @csrf
                                                            <input name="email" type="hidden"
                                                                x-bind:value="email" />
                                                            <button
                                                                type="submit">{{ __('Download (:lang)', ['lang' => Str::upper($lang)]) }}</button>
                                                        </form>
                                                    @endforeach
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </x-section>
    @endif
    @can('update', $tool)
        <div class="center center:wide">
            <p class="mt-12"><a class="cta secondary"
                    href="{{ route('filament.admin.resources.tools.edit', $tool) }}">@svg('heroicon-o-pencil', 'mr-1')
                    {{ __('Edit tool') }}</a></p>
        </div>
    @endcan
</x-app-layout>
