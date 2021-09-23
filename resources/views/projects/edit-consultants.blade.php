
<x-app-wide-layout>
    <x-slot name="title">{{ __('Find consultants') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Find consultants') }}
        </h1>
        <p>{{ $project->name }}</p>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <div class="tabs flow" x-data="tabs(window.location.hash ? window.location.hash.substring(1) : 'interested')" x-on:resize.window="enabled = window.innerWidth > 1023">
        <h2 x-show="!enabled">{{ __('Contents') }}</h2>
        <ul x-bind="tabList">
            <li x-bind="tabWrapper"><a href="#interested" x-bind="tab">{{ __('Interested in this project') }}</a></li>
            <li x-bind="tabWrapper"><a href="#meets-criteria" x-bind="tab">{{ __('Meet basic project criteria') }}</a></li>
            <li x-bind="tabWrapper"><a href="#similar-projects" x-bind="tab">{{ __('From similar projects') }}</a></li>
            <li x-bind="tabWrapper"><a href="#browse-all" x-bind="tab">{{ __('Browse all consultants') }}</a></li>
        </ul>
        <div class="flow" id="interested" x-bind="tabpanel">
            <h2>{{ __('Interested in this project') }}</h2>
        </div>
        <div class="flow" id="meets-criteria" x-bind="tabpanel">
            <h2>{{ __('Meet basic project criteria') }}</h2>
        </div>
        <div class="flow" id="similar-projects" x-bind="tabpanel">
            <h2>{{ __('From similar projects') }}</h2>
        </div>
        <div class="flow" id="browse-all" x-bind="tabpanel">
            <h2>{{ __('Browse all consultants') }}</h2>
            @foreach ($consultants as $consultant)
            <form action="" method="post">
                @csrf
                @method('put')
                {{ $consultant->name }}

                <x-hearth-button>{{ __('Save') }}</x-hearth-button>
            </form>
            @endforeach
        </div>
    </div>

    <section class="flow" aria-labelledby="saved-consultants" x-data="{expanded: false}">
        <h2 id="saved-consultants"><button x-on:click="expanded = !expanded" x-bind:aria-expanded="expanded.toString()">{{ __('Saved consultants') }}</button></h2>

        <div class="flow" x-show="expanded">
            @forelse ($project->consultants as $consultant)
            <p>{{ $consultant->name }}</p>
            @empty
            <p>{{ __('No consultants saved.') }}</p>
            @endforelse

            <form action="" method="post">
                @csrf
                @method('put')

                <x-hearth-button>{{ __('Add consultants to shortlist') }}</x-hearth-button>
            </form>
        </div>
    </section>

</x-app-wide-layout>
