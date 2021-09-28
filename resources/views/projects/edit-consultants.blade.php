
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
            {{-- TODO. --}}
            @empty
            <p>{{ __('Sorry, no consultants were found.') }}</p>
            @endforelse
        </div>
        <div class="flow" id="similar-projects" x-bind="tabpanel">
            <h2>{{ __('From similar projects') }}</h2>
            @forelse($relatedConsultants as $consultant)
            {{-- TODO. --}}
            @empty
            <p>{{ __('Sorry, no consultants were found.') }}</p>
            @endforelse
        </div>
        <div class="flow" id="browse-all" x-bind="tabpanel">
            <h2>{{ __('Browse all consultants') }}</h2>
            @forelse($consultants as $consultant)
            <form action="{{ localized_route('projects.add-consultant', $project) }}" method="post">
                @csrf
                @method('put')
                {{ $consultant->name }}

                <x-hearth-input type="hidden" name="consultant_id" :value="$consultant->id" />
                <x-hearth-input type="hidden" name="status" value="saved" />

                <x-hearth-button>{{ __('Save') }}</x-hearth-button>
            </form>
            @empty
            <p>{{ __('Sorry, no consultants were found.') }}</p>
            @endforelse
        </div>
    </div>

    @if(count($project->savedConsultants) > 0)
    <section class="drawer flow" aria-labelledby="saved-consultants" x-data="{expanded: false}">
        <h2 id="saved-consultants"><button x-on:click="expanded = !expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Saved consultants') }}</button></h2>

        <div class="flow" x-show="expanded">
            @foreach($project->savedConsultants as $consultant)
            <p>{{ $consultant->name }}</p>
            @endforeach


            <form action="{{ localized_route('projects.update-consultants', $project) }}" method="post">
                @csrf
                @method('put')

                @foreach($project->consultants as $consultant)
                <x-hearth-input type="hidden" :id="'consultant-id-' . $loop->index" name="consultant_ids[]" :value="$consultant->id" />
                @endforeach

                <x-hearth-input type="hidden" name="status" value="shortlisted" />

                <x-hearth-button>{{ __('Add consultants to shortlist') }}</x-hearth-button>
            </form>
        </div>
    </section>
    @endif

</x-app-wide-layout>
