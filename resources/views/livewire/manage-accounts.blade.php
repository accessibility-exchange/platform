<x-slot name="title">
    {{ __('Manage accounts') }}
</x-slot>

<x-slot name="header">
    <h1 id="manage-users">
        {{ __('Manage accounts') }}
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

    <div role="region" aria-labelledby="manage-users" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Account name') }}</th>
                    <th>{{ __('Account type') }}</th>
                    <th>{{ __('Page status') }}</th>
                    <th>{{ __('Approval status') }}</th>
                    <th></th>
                </tr>
            </thead>
            @foreach ($accounts as $account)
                <tr wire:key="{{ $account->getRoutePlaceholder() }}-{{ $account->id }}">
                    <td>
                        @if ($account->isPublishable())
                            <a
                                href="{{ localized_route($account->getRoutePrefix() . '.show', $account) }}"><strong>{{ $account->name }}</strong></a>
                        @else
                            <strong>{{ $account->name }}</strong>
                        @endif
                        <br />
                        <a
                            href="mailto:{{ $account->contact_person_email ?? $account->user->email }}">{{ $account->contact_person_email ?? $account->user->email }}</a>
                    </td>
                    <td>
                        {{ Str::ucfirst(__(Str::kebab(class_basename($account)) . '.singular_name')) }}
                    </td>
                    <td>
                        @if ($account->checkStatus('draft') && !$account->isPublishable())
                            {{ __('Draft') }}
                        @elseif($account->checkStatus('draft') && $account->isPublishable())
                            {{ __('Ready to publish') }}
                        @elseif($account->checkStatus('published'))
                            {{ __('Published') }}
                        @endif
                    </td>
                    <td>
                        @if ($account instanceof App\Models\Individual)
                            @if ($account->user->checkStatus('suspended'))
                                {{ __('Suspended') }}
                            @else
                                @if ($account->user->checkStatus('pending'))
                                    {{ __('Pending approval') }}
                                @elseif($account->user->checkStatus('approved'))
                                    {{ __('Approved') }}
                                @endif
                            @endif
                        @else
                            @if ($account->checkStatus('suspended'))
                                {{ __('Suspended') }}
                            @else
                                @if ($account->checkStatus('pending'))
                                    {{ __('Pending approval') }}
                                @elseif($account->checkStatus('approved'))
                                    {{ __('Approved') }}
                                @endif
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($account instanceof App\Models\Individual)
                            @if ($account->user->checkStatus('pending'))
                                <button
                                    wire:click="approveIndividual({{ $account->id }})">{{ __('Approve') }}</button>
                            @endif
                        @else
                            @if ($account->checkStatus('pending'))
                                <button
                                    wire:click="approveOrganization({{ $account->id }}, '{{ class_basename($account) }}')">{{ __('Approve') }}</button>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    {{ $accounts->onEachSide(2)->links('vendor.livewire.tailwind') }}
</div>
