<x-slot name="header">
    <h1 id="estimates-and-agreements">
        {{ __('Estimates and agreements') }}
    </h1>
</x-slot>

<div class="space-y-12">
    <form class="stack" wire:submit.prevent="search">
        <x-hearth-label for="model" :value="__('Search by organization name')" />
        <div class="repel">
            <x-hearth-input name="model" type="search" wire:model.defer="query" wire:search="search" />
            <button>{{ __('Search') }}</button>
        </div>
    </form>

    <div role="alert">
        @if ($query)
            <p class="h4">
                {{ __(':count results for “:query”', ['count' => $projects->count(), 'query' => $query]) }}
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
                        @if ($project->agreement_received_at)
                            {{ $project->agreement_received_at->format('Y-m-d') }}
                        @elseif ($project->estimate_approved_at)
                            {{ $project->estimate_approved_at->format('Y-m-d') }}
                        @elseif($project->estimate_returned_at)
                            {{ $project->estimate_returned_at->format('Y-m-d') }}
                        @elseif($project->estimate_requested_at)
                            {{ $project->estimate_requested_at->format('Y-m-d') }}
                        @endif
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

    {{ $projects->onEachSide(2)->links('vendor.livewire.tailwind') }}
</div>
