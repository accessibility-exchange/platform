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

<form class="stack" wire:submit.prevent="inviteConnector">
    <div role="alert" x-data="{ visible: false }" @add-flash-message.window="visible = true"
        @clear-flash-message.window="visible = false" <div x-show="visible" x-transition:leave.duration.500ms>
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
        <hr />

        <fieldset>
            <legend>{{ __('Community Connector') }}</legend>
            @if ($who === 'individual')
                <p>{{ __('Please enter the email address of the individual you have hired as a Community Connector.') }}
                </p>
                <div class="field @error('email') field--error @enderror">
                    <x-hearth-label for="email">{{ __('Email address') }}</x-hearth-label>
                    <x-hearth-hint for="email">{{ __('This is the email your invitation will be sent to.') }}
                    </x-hearth-hint>
                    <x-hearth-input name='email' type="email" wire:model.lazy="email" hinted />
                    <x-hearth-error for="email" />
                </div>
            @elseif($who === 'organization')
                <div class="field @error('organization') field--error @enderror">
                    <x-hearth-label for="organization">{{ __('Community organization') }}</x-hearth-label>
                    <x-hearth-select name="organization" :options="$organizations" wire:model="organization" />
                    <x-hearth-error for="organization" />
                </div>
            @endif
    @endif
    </fieldset>
    <hr class="divider--thick" />
    <div class="flex flex-row gap-6">
        <a class="cta secondary" href="{{ localized_route('engagements.manage-connector', $engagement) }}">
            @svg('heroicon-o-arrow-left') {{ __('Cancel') }}
        </a>
        @if ($who === 'individual')
            <button>{{ __('Send invitation') }} @if ($email)
                    <span class="sr-only">{{ __('to :email', ['email' => $email]) }}</span>
                @endif
            </button>
        @elseif($who === 'organization')
            <button>{{ __('Send invitation') }} @if ($organization)
                    <span
                        class="sr-only">{{ __('to :organization', ['organization' => App\Models\Organization::find($organization)->name]) }}</span>
                @endif
            </button>
        @endif
    </div>
</form>
