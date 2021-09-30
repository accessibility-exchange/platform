
<x-app-wide-layout>
    <x-slot name="title">{{ __('Find consultants') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Find consultants') }}
        </h1>
        <p>{{ $project->name }}</p>
        <p><a href="{{ localized_route('projects.manage', $project) }}">{{ __('Return to project dashboard') }}</a></p>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <div class="tabs flow" x-data="tabs(window.location.hash ? window.location.hash.substring(1) : 'interested')" x-on:resize.window="enabled = window.innerWidth > 1023">
        <h2 x-show="!enabled">{{ __('Contents') }}</h2>
        <ul x-bind="tabList">
            <li x-bind="tabWrapper"><a href="#interested" x-bind="tab">{{ __('Interested in this project') }}</a></li>
            <li x-bind="tabWrapper"><a href="#similar-projects" x-bind="tab">{{ __('From similar projects') }}</a></li>
            <li x-bind="tabWrapper"><a href="#browse-all" x-bind="tab">{{ __('Browse all consultants') }}</a></li>
        </ul>
        <div class="flow" id="interested" x-bind="tabpanel">
            <h2>{{ __('Interested in this project') }}</h2>
            @forelse($interestedConsultants as $consultant)
            <x-consultant-card level="3" :consultant="$consultant">
                <x-slot name="actions">
                    <form action="{{ localized_route('projects.add-consultant', $project) }}" method="post">
                        @csrf
                        @method('put')

                        <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />

                        <x-hearth-button>{!! __('Add <span class="visually-hidden">:name</span> to shortlist', ['name' => $consultant->name]) !!}</x-hearth-button>
                    </form>
                </x-slot>
            </x-consultant-card>
            @empty
            <p>{{ __('Sorry, no consultants were found.') }}</p>
            @endforelse
        </div>
        <div class="flow" id="similar-projects" x-bind="tabpanel">
            <h2>{{ __('From similar projects') }}</h2>
            @forelse($relatedConsultants as $consultant)
            <x-consultant-card level="3" :consultant="$consultant">
                <x-slot name="actions">
                    <form action="{{ localized_route('projects.add-consultant', $project) }}" method="post">
                        @csrf
                        @method('put')

                        <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />

                        <x-hearth-button>{!! __('Add <span class="visually-hidden">:name</span> to shortlist', ['name' => $consultant->name]) !!}</x-hearth-button>
                    </form>
                </x-slot>
            </x-consultant-card>
            @empty
            <p>{{ __('Sorry, no consultants were found.') }}</p>
            @endforelse
        </div>
        <div class="flow" id="browse-all" x-bind="tabpanel">
            <h2>{{ __('Browse all consultants') }}</h2>
            @forelse($consultants as $consultant)
            <x-consultant-card level="3" :consultant="$consultant">
                <x-slot name="actions">
                    <form action="{{ localized_route('projects.add-consultant', $project) }}" method="post">
                        @csrf
                        @method('put')

                        <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />

                        <x-hearth-button>{!! __('Add <span class="visually-hidden">:name</span> to shortlist', ['name' => $consultant->name]) !!}</x-hearth-button>
                    </form>
                </x-slot>
            </x-consultant-card>

            @empty
            <p>{{ __('Sorry, no consultants were found.') }}</p>
            @endforelse
        </div>
    </div>

    @if(count($project->shortlistedConsultants) > 0)
    <section class="drawer flow" aria-labelledby="shortlisted-consultants" x-data="{expanded: false}">
        <h2 id="shortlisted-consultants"><button x-on:click="expanded = !expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Shortlisted consultants') }} <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" /></button></h2>

        <div class="flow" x-show="expanded" x-cloak>
            @foreach($project->shortlistedConsultants as $consultant)
            <x-consultant-card :consultant="$consultant" level="3">
                <x-slot name="actions">
                    <form action="{{ localized_route('projects.remove-consultant', $project) }}" method="post">
                        @csrf
                        @method('put')

                        <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
                        <x-hearth-input type="hidden" name="status" value="requested" />

                        <x-hearth-button>{{ __('Remove') }} <span class="visually-hidden">{{ $consultant->name }}</span></x-hearth-button>
                    </form>
                </x-slot>
            </x-consultant-card>
            @endforeach

            <p><a href="{{ localized_route('projects.manage', $project) }}">{{ __('Review shortlist') }}</a></p>
        </div>
    </section>
    @endif

</x-app-wide-layout>
