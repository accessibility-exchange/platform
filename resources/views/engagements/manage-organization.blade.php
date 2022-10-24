<x-app-medium-layout>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.manage', $project) }}">{{ $project->name }}</a></li>
            <li><a href="{{ localized_route('engagements.manage', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1>
            {{ __('Community organization') }}
        </h1>
    </x-slot>

    @if ($engagement->organization)
        <x-card.organization :model="$engagement->organization" />
        <form action="{{ localized_route('engagements.remove-organization', $engagement) }}" method="post">
            @csrf

            <button class="secondary destructive -mt-6">
                <x-heroicon-o-trash class="h-5 w-5" aria-hidden="true" /> {{ __('Remove') }}
            </button>
        </form>
    @else
        <p><a href="{{ localized_route('organizations.index') }}">{{ __('Browse community organizations') }}</a></p>
        <p>{{ __('Once you have hired a Community Organization, please select the organization below.') }}</p>

        <form action="{{ localized_route('engagements.add-organization', $engagement) }}" method="post">
            @csrf

            <div class="field @error('organization_id') field--error @enderror">
                <x-hearth-label for="organization_id">{{ __('Community Organization') }}</x-hearth-label>
                <x-hearth-select name="organization_id" :options="$organizations" :selected="$engagement->organization?->id" />
                <x-hearth-error for="organization_id" />
            </div>

            <hr class="divider--thick" />

            <button>{{ __('Save') }}</button>
        </form>
    @endif
</x-app-medium-layout>
