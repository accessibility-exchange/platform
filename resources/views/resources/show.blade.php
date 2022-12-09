<x-app-layout header-class="header--tabbed" page-width="wide">
    <x-slot name="title">{{ $resource->title }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('resource-collections.index') }}">{{ __('Resources') }}</a></li>
                <li><a href="{{ localized_route('resources.index') }}">{{ __('Browse all resources') }}</a></li>
            </ol>
            <h1>
                {{ $resource->title }}
            </h1>
            {{-- TODO: Handle author link --}}
            <p>{!! Str::inlineMarkdown(
                __('**:content_type** by :author', [
                    'content_type' => $resource->contentType?->name ?? __('Resource'),
                    'author' => $resource->author ?? __('The Accessibility Exchange'),
                ]),
            ) !!}</p>
            <div class="my-12 flex flex-col gap-6 md:grid md:grid-cols-3">
                {{-- TODO: Handle topics --}}
                <div class="stack">
                    <p><strong>{{ __('Phases of consultation') }}</strong></p>
                    <ul class="tags" role="list">
                        @forelse($resource->phases ?? [] as $phase)
                            <li class="tag">{{ App\Enums\ConsultationPhase::labels()[$phase] }}</li>
                        @empty
                            <li>{{ __('None listed') }}</li>
                        @endforelse
                    </ul>
                </div>
                <div class="stack">
                    <p><strong>{{ __('Sectors') }}</strong></p>
                    <ul class="tags" role="list">
                        @forelse($resource->sectors ?? [] as $sector)
                            <li class="tag">{{ $sector->name }}</li>
                        @empty
                            <li>{{ __('None listed') }}</li>
                        @endforelse
                    </ul>
                </div>
                <div class="stack">
                    <p><strong>{{ __('Areas of impact') }}</strong></p>
                    <ul class="tags" role="list">
                        @forelse($resource->impacts ?? [] as $impact)
                            <li class="tag">{{ $impact->name }}</li>
                        @empty
                            <li>{{ __('None listed') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <p><em>{{ __('Added on :date', ['date' => $resource->created_at->isoFormat('LL')]) }}</em></p>
        </div>
    </x-slot>

    <div class="stack py-12 md:w-2/3">
        @if ($resource->summary)
            <h2>{{ __('Summary') }}</h2>

            {!! Str::markdown($resource->summary) !!}
        @endif

        <h2>{{ __('Links to resource') }}</h2>
        <ul class="link-list" role="list">
            @foreach ($resource->getTranslations('url') as $code => $url)
                <li>
                    <a href="{{ $url }}"><span class="sr-only">{{ __('access resource in') }}</span>
                        {{ get_language_exonym($code) }}</a>
                </li>
            @endforeach
        </ul>

        @can('update', $resource)
            <p class="mt-12"><a class="cta secondary"
                    href="{{ route('filament.resources.resources.edit', $resource) }}">@svg('heroicon-o-pencil', 'mr-1')
                    {{ __('Edit resource') }}</a></p>
        @endcan
    </div>
    @if ($resource->resourceCollections->count())
        <div class="full accent -mb-8 pt-12 pb-8">
            <div class="center center:wide stack">
                <h2>{{ __('Collections this resource appears in') }}</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($resource->resourceCollections as $resourceCollection)
                        <x-card.resource-collection :model="$resourceCollection" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
