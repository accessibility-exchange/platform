<x-app-layout header-class="header--tabbed" page-width="wide">
    <x-slot name="title">{{ $title ?? __('Engagements I’ve joined') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 id="projects" itemprop="name">
                    {{ __('Engagements I’ve joined') }}
                </h1>
                @if (Auth::user()->can('viewAny', 'App\Models\Engagement'))
                    <a class="cta secondary"
                        href="{{ localized_route('engagements.index') }}">{{ __('Browse all engagements') }}</a>
                @endif
            </div>
            <x-interpretation name="{{ __('Engagements I’ve joined', [], 'en') }}" namespace="engagements-joined" />
        </div>
    </x-slot>

    <nav class="nav--tabbed" aria-labelledby="projects">
        <div class="center center:wide">
            <ul class="-mt-4 flex gap-6" role="list">
                @if ($showParticipating)
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('engagements.joined-participating')"
                            :active="$section === App\Enums\ProjectInvolvement::Participating->value">
                            {{ __('Joined as a Consultation Participant') }}
                        </x-nav-link>
                    </li>
                @endif
                @if ($showConnecting)
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('engagements.joined-contracted')"
                            :active="$section === App\Enums\ProjectInvolvement::Contracted->value">
                            {{ __('Joined as a Community Connector') }}
                        </x-nav-link>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    @includeIf("engagements.joined.{$section}")
</x-app-layout>
