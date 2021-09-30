
<x-app-layout>
    <x-slot name="title">{{ __('Find consultants') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Find consultants') }}
        </h1>
        <p>{{ $project->name }}</p>
        <p><a href="{{ localized_route('projects.manage', ['project' => $project, 'step' => 2]) }}">{{ __('Return to project dashboard') }}</a></p>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <nav>
        <ul role="list">
            <x-nav-link :href="localized_route('projects.find-interested-consultants', $project)" :active="request()->routeIs(locale() . '.projects.find-interested-consultants')">{{ __('Interested in this project') }}</x-nav-link>
            <x-nav-link :href="localized_route('projects.find-related-consultants', $project)" :active="request()->routeIs(locale() . '.projects.find-related-consultants')">{{ __('From similar projects') }}</x-nav-link>
            <x-nav-link :href="localized_route('projects.find-all-consultants', $project)" :active="request()->routeIs(locale() . '.projects.find-all-consultants')">{{ __('Browse all consultants') }}</x-nav-link>
        </ul>
    </nav>

    <div class="flow" id="browse-all">
        <h2>{{ __('Browse all consultants') }}</h2>
        @forelse($consultants as $consultant)
        <x-consultant-card level="3" :consultant="$consultant" :project="$project">
            <x-slot name="actions">
                <div class="actions">
                    <form action="{{ localized_route('projects.add-consultant', $project) }}" method="post">
                        @csrf
                        @method('put')
                        <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
                        <x-hearth-button>{!! __('Add <span class="visually-hidden">:name</span> to shortlist', ['name' => $consultant->name]) !!}</x-hearth-button>
                    </form>
                </div>
            </x-slot>
        </x-consultant-card>

        @empty
        <p>{{ __('Sorry, no consultants were found.') }}</p>
        @endforelse

        {{-- {{ $consultants->links() }} TODO: Set up pagination --}}
    </div>

    @if(count($project->shortlistedConsultants) > 0)
    <section class="drawer flow" aria-labelledby="shortlisted-consultants" x-data="{expanded: false}">
        <h2 id="shortlisted-consultants"><button x-on:click="expanded = !expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Shortlisted consultants') }} <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" /></button></h2>

        <div class="flow" x-show="expanded" x-cloak>
            @foreach($project->shortlistedConsultants as $consultant)
            <x-consultant-card :consultant="$consultant" :project="$project" level="3">
                <x-slot name="actions">
                    <div class="actions">
                        <form action="{{ localized_route('projects.remove-consultant', $project) }}" method="post">
                            @csrf
                            @method('put')
                            <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
                            <x-hearth-input type="hidden" name="status" value="requested" />
                            <x-hearth-button>{{ __('Remove') }} <span class="visually-hidden">{{ $consultant->name }}</span></x-hearth-button>
                        </form>
                    </div>
                </x-slot>
            </x-consultant-card>
            @endforeach

            <p><a href="{{ localized_route('projects.manage', ['project' => $project, 'step' => 2]) }}">{{ __('Review shortlist') }}</a></p>
        </div>
    </section>
    @endif

</x-app-layout>
