<x-slot name="title">{{ __('Libraries') }}</x-slot>
<x-slot name="header">
    <div class="center center:wide stack pb-12 pt-4">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('resources-and-training') }}">{{ __('Resources and training') }}</a></li>
        </ol>
        <h1 itemprop="name">{{ __('Browse all libraries') }}</h1>
        <p class="subtitle">
            {{ __('Libraries are extensive and curated collections of resources that help you achieve a specific learning goal.') }}
        </p>
        <x-interpretation name="{{ __('Browse all libraries', [], 'en') }}" />
    </div>
</x-slot>
<x-section class="px-0">
    <div class="center center:wide stack stack:xl px-0">
        <div class="row flex items-center justify-end gap-6">
            <x-hearth-label for="orderBy">{{ __('Sort by') }}</x-hearth-label>
            <x-hearth-select class="w-auto" name="orderBy" :options="$orderOptions" wire:model.live="orderBy" />
        </div>
        @if ($libraries->count() > 0)
            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($libraries as $library)
                    <x-card.library :model="$library" />
                @endforeach
            </div>
        @else
            <p>{{ __('No libraries found.') }}</p>
        @endif

        {{ $libraries->onEachSide(2)->links('vendor.livewire.tailwind-custom') }}
    </div>
</x-section>
