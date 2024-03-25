<x-app-layout header-class="header--tabbed" page-width="wide">
    <x-slot name="title">{{ __('Projects my organization has created') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack">
            <div class="flex items-center justify-between gap-4">
                <h1 id="projects" itemprop="name">
                    {{ __('Projects my organization has created') }}
                </h1>
                @can('create', 'App\Models\Project')
                    <a class="cta shrink-0"
                        href="{{ $projectable->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create new project') }}</a>
                @endcan
            </div>
            <x-interpretation name="{{ __('Projects my organization has created', [], 'en') }}" />
        </div>
    </x-slot>

    @includeWhen($projectable, 'projects.my-projects.running')
</x-app-layout>
