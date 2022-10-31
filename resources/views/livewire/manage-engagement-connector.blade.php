<x-slot name="header">
    <ol class="breadcrumbs" role="list">
        <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
    </ol>
    <h1>
        {{ __('Community Connector') }}
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

    @if (!$engagement->connector && !$engagement->organizationalConnector && !$invitation)
        <h2>{{ __('Find a Community Connector') }}</h2>

        <p>{{ __('If you are seeking a Community Connector for this engagement, there are a few ways to find one:') }}
        </p>

        <h3>{{ __('Show that you are looking for a Community Connector') }}</h3>

        <p>{!! __(
            'This will show Community Connectors on the :browse page that you are looking, and that they are welcome to reach out.',
            [
                'browse' => '<a href="' . localized_route('projects.all-projects') . '">' . __('browse projects') . '</a>',
            ],
        ) !!}
        </p>

        <div class="field">
            <x-hearth-checkbox name="seeking_community_connector" wire:model="seeking_community_connector"
                wire:click="updateStatus" />
            <x-hearth-label for="seeking_community_connector">
                {{ __('I am currently seeking an Community Connector for this engagement') }}</x-hearth-label>
        </div>

        <hr class="border-t-1 mt-16 mb-12 border-x-0 border-b-0 border-solid border-t-blue-7" />

        <h3>{{ __('Browse for an Community Connector') }}</h3>

        <p>{{ __('Go through our listings of Community Connectors on this website.') }}</p>

        <p>
            <a class="cta secondary"
                href="{{ localized_route('people-and-organizations.connectors') }}">{{ __('Browse Community Connectors') }}</a>
        </p>

        <hr class="divider--thick" />
    @endif

    <h2>{{ __('Manage Community Connector') }}</h2>

    @if (!$engagement->connector && !$engagement->organizationalConnector && !$invitation)
        <p>{{ __('Once you have hired a Community Connector, please add them here. This will give them access to your engagement details and allow them to add participants.') }}
        </p>

        <p>
            <a class="cta secondary" href="{{ localized_route('engagements.add-connector', $engagement) }}">
                @svg('heroicon-o-plus-circle')
                {{ __('Add Community Connector') }}
            </a>
        </p>
    @else
        @if ($invitation)
            @if ($invitation->type === 'individual')
                @if ($invitee)
                    <x-card.individual level="3" :model="$invitee" />
                @else
                    <p>{{ $invitation->email }} <span class="badge">{{ __('Pending') }}</span></p>
                @endif
            @elseif($invitation->type === 'organization')
                <x-card.organization level="3" :model="$invitee" />
            @endif
            <button class="borderless destructive" wire:click="cancelInvitation">
                @svg('heroicon-s-x-mark') {{ __('Cancel invitation') }}
            </button>
        @elseif($engagement->connector || $engagement->organizationalConnector)
            @if ($engagement->connector)
                <x-card.individual level="3" :model="$engagement->connector" />
            @elseif($engagement->organizationalConnector)
                <x-card.organization level="3" :model="$engagement->organizationalConnector" />
            @endif
            <button class="borderless destructive" wire:click="removeConnector">
                @svg('heroicon-s-trash') {{ __('Remove') }}
            </button>
        @endif
    @endif

    <hr class="divider--thick" />

    <p>
        <a class="cta secondary" href="{{ localized_route('engagements.manage', $engagement) }}">
            @svg('heroicon-o-arrow-left') {{ __('Back') }}
        </a>
    </p>
</div>
