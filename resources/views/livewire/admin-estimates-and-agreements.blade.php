<x-slot name="title">
    {{ __('Estimates and agreements') }}
</x-slot>

<x-slot name="header">
    <ol class="breadcrumbs" role="list">
        <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
    </ol>
    <h1 id="estimates-and-agreements">
        {{ __('Estimates and agreements') }}
    </h1>
</x-slot>

<div class="space-y-12">
    <div role="alert" x-data="{ visible: false }" @add-flash-message.window="visible = true"
        @clear-flash-message.window="visible = false"
        @remove-flash-message.window="setTimeout(() => visible = false, 5000)">
        <div x-show="visible" x-transition:leave.duration.500ms>
            @if (session()->has('message'))
                <x-hearth-alert type="success">
                    {!! Str::markdown(session('message')) !!}
                </x-hearth-alert>
            @endif
        </div>
    </div>

    <form class="stack" wire:submit.prevent="search">
        <x-hearth-label for="searchQuery" :value="__('Search by organization name')" />
        <div class="repel">
            <x-hearth-input name="searchQuery" type="search" wire:model.defer="searchQuery" wire:search="search" />
            <button>{{ __('Search') }}</button>
        </div>
    </form>

    <div role="alert">
        @if ($searchQuery)
            <p class="h4">
                {{ __(':count results for “:searchQuery', ['count' => $projects->total(), 'searchQuery' => $searchQuery]) }}
            </p>
        @endif
    </div>

    <div role="region" aria-labelledby="estimates-and-agreements" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>{{ __('project.singular_name_titlecase') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Status updated') }}</th>
                    <th></th>
                </tr>
            </thead>
            @forelse ($projects as $project)
                <tr>
                    <td>
                        <a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a><br />
                        <span class="font-normal">{{ $project->projectable->name }}</span>
                    </td>
                    <td>
                        @if ($project->estimate_approved_at)
                            {{ __('Estimate approved') }}
                        @elseif($project->estimate_returned_at)
                            {{ __('Estimate returned') }}
                        @elseif($project->estimate_requested_at)
                            {{ __('Estimate requested') }}
                        @endif
                        @if ($project->estimate_returned_at)
                            <br />
                            @if ($project->agreement_received_at)
                                {{ __('Agreement received') }}
                            @else
                                {{ __('Agreement pending') }}
                            @endif
                        @endif
                    </td>
                    <td>
                        {{ $project->estimate_or_agreement_updated_at->format('Y-m-d') }}
                    </td>
                    <td>
                        @if ($project->estimate_returned_at && !$project->agreement_received_at)
                            <button class="secondary"
                                wire:click="markAgreementReceived({{ $project->id }})">{{ __('Mark agreement as received') }}
                                <span class="sr-only">{{ __('for :project', ['project' => $project->name]) }}</span>
                            </button>
                        @elseif(!$project->estimate_returned_at)
                            <button class="secondary"
                                wire:click="markEstimateReturned({{ $project->id }})">{{ __('Mark estimate as returned') }}
                                <span class="sr-only">{{ __('for :project', ['project' => $project->name]) }}</span>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </table>
    </div>

    {{ $projects->onEachSide(2)->links('vendor.livewire.tailwind-custom') }}
</div>
