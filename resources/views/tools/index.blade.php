<x-app-layout page-width="wide" header-class="stack full header--tools">
    <x-slot name="title">{{ __('Tools') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack pb-12 pt-4">
            <h1 itemprop="name">{{ __('Tools') }}</h1>
            <p class="subtitle">
                {{ __('Description TODO.') }}
            </p>
            <x-interpretation name="{{ __('Tools', [], 'en') }}" />
        </div>
    </x-slot>
    <x-section>
        <div class="center center:wide stack stack:xl">
            @if ($tools->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($tools as $tool)
                        <x-card.tool :model="$tool" />
                    @endforeach
                </div>
            @else
                <p>{{ __('No tools found.') }}</p>
            @endif
        </div>
    </x-section>

</x-app-layout>
