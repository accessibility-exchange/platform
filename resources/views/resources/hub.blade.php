<x-app-wide-layout>
    <x-slot name="title">{{ __('Resource hub') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('Resource hub') }}</h1>
        <p class="subtitle">{{ __('Find learning materials, best practices, and variety of tools to help you throughout the consultation process.') }}</p>
    </x-slot>

    <div class="flow">
        <h2>{{ __('Search') }}</h2>
        <form class="search" action="" method="post">
            @csrf
            <label for="search" class="visually-hidden">{{ __('Search') }}</label>
            <input id="search" type="search" />
            <button type="submit">{{ __('Search') }}</button>
        </form>
        <h2>{{ __('Browse all resources') }}</h2>
        <p>{{ __('Explore our entire resource hub.') }}</p>
        <p><a href="{{ localized_route('resources.index') }}">{{ __('Browse all resources') }}</a></p>
    </div>
    <div class="flow">
        <h2>{{ __('Resources based on your role') }}</h2>
        <div class="cards cards--collections">
            <div class="card card--collection flow">
                <h3 id="new-consultants">{{ __('New consultants') }}</h3>
                <p>{{ __('Check out these resources to get ready for accessibility consultation.') }}</p>
                <p class="actions"><a class="button" href="#" aria-describedby="new-consultants">{{ __('Visit resources') }}</a></p>
            </div>
            <div class="card card--collection flow">
                <h3 id="experienced-consultants">{{ __('Experienced consultants') }}</h3>
                <p>{{ __('Build up your skills and refine your practice to tackle more challenging projects.') }}</p>
                <p class="actions"><a class="button" href="#" aria-describedby="experienced-consultants">{{ __('Visit resources') }}</a></p>
            </div>
            <div class="card card--collection flow">
                <h3 id="federally-regulated-entities">{{ __('Federally regulated entities') }}</h3>
                <p>{{ __('Explore best practices, tools, and resources that help you set up for accessibility projects.') }}</p>
                <p class="actions"><a class="button" href="#" aria-describedby="federally-regulated-entities">{{ __('Visit resources') }}</a></p>
            </div>
            <div class="card card--collection flow">
                <h3 id="deaf-and-disability-organizations">{{ __('Deaf and Disability organizations') }}</h3>
                <p>{{ __('Support your members on their path of becoming accessibility consultants.') }}</p>
                <p class="actions"><a class="button" href="#" aria-describedby="deaf-and-disability-organizations">{{ __('Visit resources') }}</a></p>
            </div>
        </div>
        <h2>{{ __('Resources based on stages of consultation') }}</h2>
        <div class="cards cards--collections">
            <div class="card card--collection flow">
                <h3 id="resources-to-prepare-for-consultation">{{ __('Resources to prepare for consultation') }}</h3>
                <p>{{ __('Tools, stories, and guides about outreach, recruitment, and planning a consultation') }}</p>
                <p class="actions"><a class="button" href="#" aria-describedby="resources-to-prepare-for-consultation">{{ __('Visit resources') }}</a></p>
            </div>
            <div class="card card--collection flow">
                <h3 id="resources-to-go-through-consultation">{{ __('Resources to go through consultation') }}</h3>
                <p>{{ __('Tools, stories, and guides to hold accessible consultations') }}</p>
                <p class="actions"><a class="button" href="#" aria-describedby="resources-to-go-through-consultation">{{ __('Visit resources') }}</a></p>
            </div>
            <div class="card card--collection flow">
                <h3 id="resources-to-prepare-acccessibility-plans">{{ __('Resources to prepare accessibility plans') }}</h3>
                <p>{{ __('Tools, stories, and guides to translate consultation results into accessibility plans') }}</p>
                <p class="actions"><a class="button" href="#" aria-describedby="resources-to-prepare-acccessibility-plans">{{ __('Visit resources') }}</a></p>
            </div>
        </div>
        <h2>{{ __('Resource based on topics') }}</h2>
        <div class="cards cards--collections">
            <div class="card card--collection flow">
                <h3 id="disability-knowledge-and-awareness">{{ __('Disability knowledge and awareness') }}</h3>
                <p class="actions"><a class="button" href="#" aria-describedby="disability-knowledge-and-awareness">{{ __('Visit resources') }}</a></p>
            </div>
            <div class="card card--collection flow">
                <h3 id="accessible-and-intersectional-consultation">{{ __('Accessible and intersectional consultation') }}</h3>
                <p class="actions"><a class="button" href="#" aria-describedby="accessible-and-intersectional-consultation">{{ __('Visit resources') }}</a></p>
            </div>
            <div class="card card--collection flow">
                <h3 id="best-practices-and-guidelines">{{ __('Best practices and guidelines') }}</h3>
                <p class="actions"><a class="button" href="#" aria-describedby="best-practices-and-guidelines">{{ __('Visit resources') }}</a></p>
            </div>
            <div class="card card--collection flow">
                <h3 id="legal-and-financial-awareness">{{ __('Legal and financial awareness') }}</h3>
                <p class="actions"><a class="button" href="#" aria-describedby="legal-and-financial-awareness">{{ __('Visit resources') }}</a></p>
            </div>
        </div>
    </div>
    <div class="flow">
        <h2>{{ __('Stories from Deaf and Disability communities') }}</h2>
        <p><a href="{{ localized_route('stories.index') }}">{{ __('Browse all stories') }}</a></p>
    </div>
</x-app-wide-layout>
