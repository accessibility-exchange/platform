
<x-app-wide-layout>
    <x-slot name="title">{{ __('Find participants') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Find participants') }}
        </h1>
        <p>{{ $project->name }}</p>
        <p><a href="{{ localized_route('projects.manage', ['project' => $project, 'step' => 2]) }}">{{ __('Return to project dashboard') }}</a></p>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <div class="has-nav-secondary">
        <nav class="secondary" aria-labelledby="project">
            <ul role="list">
                <x-nav-link :href="localized_route('projects.find-interested-participants', $project)" :active="request()->routeIs(locale() . '.projects.find-interested-participants')">{{ __('Interested in this project') }}</x-nav-link>
                <x-nav-link :href="localized_route('projects.find-related-participants', $project)" :active="request()->routeIs(locale() . '.projects.find-related-participants')">{{ __('From similar projects') }}</x-nav-link>
                <x-nav-link :href="localized_route('projects.find-all-participants', $project)" :active="request()->routeIs(locale() . '.projects.find-all-participants')">{{ __('Browse all community members') }}</x-nav-link>
            </ul>
        </nav>

        <div class="find__list flow">
            <h2>{{ $subtitle }}</h2>
            <div class="grid">
                @forelse($communityMembers as $communityMember)
                <x-community-member-card level="3" :communityMember="$communityMember" :project="$project">
                    <x-slot name="actions">
                        <div class="actions">
                            <form action="{{ localized_route('projects.add-participant', $project) }}" method="post">
                                @csrf
                                @method('put')
                                <x-hearth-input type="hidden" name="participant_id" :value="$communityMember->id" />
                                    <x-hearth-button>{!! __('Add <span class="visually-hidden">:name</span> to shortlist', ['name' => $communityMember->name]) !!}</x-hearth-button>
                                </form>
                            </div>
                        </x-slot>
                </x-community-member-card>
                @empty
                <p>{{ __('Sorry, no community members were found.') }}</p>
                @endforelse
            </div>


            {{-- {{ $communityMembers->links() }} TODO: Set up pagination --}}
        </div>
    </div>

    @if(count($project->shortlistedParticipants) > 0)
    <section class="drawer flow" aria-labelledby="shortlisted-participants" x-data="{expanded: false}">
        <h2 id="shortlisted-participants"><button x-on:click="expanded = !expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Shortlisted participants') }} <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" /></button></h2>

        <div class="flow" x-show="expanded" x-cloak>
            @foreach($project->shortlistedParticipants as $communityMember)
            <x-community-member-card :communityMember="$communityMember" :project="$project" level="3">
                <x-slot name="actions">
                    <div class="actions">
                        <form action="{{ localized_route('projects.remove-participant', $project) }}" method="post">
                            @csrf
                            @method('put')
                            <x-hearth-input type="hidden" name="participant_id" :value="$communityMember->id" />
                            <x-hearth-input type="hidden" name="status" value="requested" />
                            <x-hearth-button>{{ __('Remove') }} <span class="visually-hidden">{{ $communityMember->name }}</span></x-hearth-button>
                        </form>
                    </div>
                </x-slot>
            </x-community-member-card>
            @endforeach

            <p><a href="{{ localized_route('projects.manage', ['project' => $project, 'step' => 2]) }}">{{ __('Review shortlist') }}</a></p>
        </div>
    </section>
    @endif

</x-app-wide-layout>
