<x-slot name="title">
    {{ __('Manage accounts') }}
</x-slot>

<x-slot name="header">
    <ol class="breadcrumbs" role="list">
        <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
    </ol>
    <h1 id="manage-accounts">
        {{ __('Manage accounts') }}
    </h1>
</x-slot>

<div class="space-y-12">
    <div role="alert" x-data="{ visible: false }" @add-flash-message.window="visible = true"
        @clear-flash-message.window="visible = false">
        <div x-show="visible" x-transition:leave.duration.500ms>
            @if (session()->has('message'))
                <x-hearth-alert type="success">
                    {!! Str::markdown(session('message')) !!}
                </x-hearth-alert>
            @endif
        </div>
    </div>

    <form class="stack" wire:submit.prevent="search">
        <x-hearth-label for="searchQuery" :value="__('Search by account name')" />
        <div class="repel">
            <x-hearth-input name="searchQuery" type="search" wire:model.defer="searchQuery" wire:search="search" />
            <button>{{ __('Search') }}</button>
        </div>
    </form>

    <div role="alert">
        @if ($searchQuery)
            <p class="h4">
                {{ __(':count results for “:searchQuery”.', ['count' => $accounts->total(), 'searchQuery' => $searchQuery]) }}
            </p>
        @endif
    </div>

    <div role="region" aria-labelledby="manage-accounts" tabindex="0">
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
                @if ($account instanceof App\Models\Individual)
                    <livewire:manage-individual-account wire:key="individual-{{ $account->id }}" :user="$account->user" />
                @else
                    <livewire:manage-organizational-account
                        wire:key="{{ $account->getRoutePrefix() }}-{{ $account->id }}" :account="$account" />
                @endif
            @endforeach
        </table>
    </div>

    {{ $accounts->onEachSide(2)->links('vendor.livewire.tailwind-custom') }}
</div>
