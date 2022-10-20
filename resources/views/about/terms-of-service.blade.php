<x-app-medium-layout>
    <x-slot name="title">{{ __('Terms of Service') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessbility Exchange') }}</a></li>
        </ol>
        <h1>
            {{ __('Terms of Service') }}
        </h1>
    </x-slot>

    <div class="stack stack:xl">
        <x-card.individual :model="App\Models\Individual::status('published')->first()" />
        <x-card.organization :model="App\Models\Organization::status('published')->first()" />
        <x-card.regulated-organization :model="App\Models\RegulatedOrganization::status('published')->first()" />
        <x-card.project :model="App\Models\Project::status('published')->first()" />
        <div class="card">
            <x-card.engagement :model="App\Models\Engagement::status('published')->first()" />
        </div>
        <x-card.project :model="App\Models\Project::status('published')->first()" />
        <x-card.engagement :model="App\Models\Engagement::status('published')->first()" />
    </div>

    <x-manage-section class="mt-12" title="Engagements">
        <x-card.engagement :model="App\Models\Engagement::status('published')->first()" />
        <x-card.engagement :model="App\Models\Engagement::status('published')->first()" />
    </x-manage-section>

    <x-manage-section class="mt-12" title="Community Connector">
        <x-card.individual :model="App\Models\Individual::status('published')->first()" />
    </x-manage-section>

    <x-manage-section class="mt-12" title="Community Organization">
        <x-card.organization :model="App\Models\Organization::status('published')->first()" />
    </x-manage-section>

</x-app-medium-layout>
