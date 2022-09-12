<x-slot name="header">
    <ol class="breadcrumbs" role="list">
        <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
        <li>
            <a
                href="{{ localized_route('engagements.manage-connector', $engagement) }}">{{ __('Manage Community Connector') }}</a>
        </li>
    </ol>
    <h1>
        {{ __('Add Community Connector') }}
    </h1>
</x-slot>

<div class="stack">
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

    <p>{{ __('Once you have hired a Community Connector, please add their information here.') }}</p>

    <fieldset class="field">
        <legend>
            {{ __('Please indicate whether your Community Connector is an individual or community organization.') }}
        </legend>
        <x-hearth-radio-buttons name="who" :options="[
            ['value' => 'individual', 'label' => __('Individual')],
            ['value' => 'organization', 'label' => __('Community organization')],
        ]" wire:model="who" />
    </fieldset>

    @if ($who)
        <hr class="border-t-1 mt-16 mb-12 border-x-0 border-b-0 border-solid border-t-blue-7" />

        <fieldset>
            <legend>{{ __('Community Connector') }}</legend>
            @if ($who === 'individual')
                <p>{{ __('Please enter the email address of your Community Connector.') }}</p>

                <div class="field @error('email') field--error @enderror">
                    <x-hearth-label for="email">{{ __('Email address') }}</x-hearth-label>
                    <x-hearth-hint for="email">{{ __('This is the email your invitation will be sent to.') }}
                    </x-hearth-hint>
                    <x-hearth-input name='email' type="email" wire:model.lazy="email" hinted />
                    <x-hearth-error for="email" />
                </div>
            @elseif($who === 'organization')
                <p>{{ __('Please make sure your Community Connector is a registered member on this website.') }}
                </p>
                <livewire:model-search model="App\Models\Organization" :label="__('Find your organization')" />
                <x-hearth-input name='email' type="email" wire:model.lazy="email" :value="$organization?->contact_person_email" hinted />
            @endif
    @endif
    </fieldset>
    <hr class="mt-16 mb-12 border-x-0 border-t-3 border-b-0 border-solid border-t-blue-7" />
    <div class="flex flex-row gap-6">
        <a class="cta secondary"
            href="{{ localized_route('engagements.manage-connector', $engagement) }}">{{ __('Cancel') }}</a>
        @if ($who === 'individual')
            <button wire:click="inviteIndividual">{{ __('Send invitation') }}</button>
        @elseif($who === 'organization')
            <button wire:click="inviteOrganization">{{ __('Send invitation') }}</button>
        @endif
    </div>
</div>
