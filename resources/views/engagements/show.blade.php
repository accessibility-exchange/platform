<x-app-wide-layout>
    <x-slot name="title">{{ $engagement->name }}</x-slot>
    <x-slot name="header">
        <h1 id="engagement">
            {{ $engagement->name }}
        </h1>
        <p>{!! __('Engagement as part of :project', [
            'project' => '<a href="' . localized_route('projects.show', $project) . '">' . $project->name . '</a>',
        ]) !!}</p>
        @can('update', $project)
            <a class="button"
                href="{{ localized_route('engagements.manage', $engagement) }}">{{ __('Engagement dashboard') }}</a>
        @endcan
    </x-slot>

    <div class="has-nav-secondary">
        <nav class="secondary" aria-labelledby="engagement">
            <ul role="list">
                <x-nav-link :href="localized_route('engagements.show', $engagement)" :active="request()->localizedRouteIs('engagements.show')">{{ __('Engagement overview') }}</x-nav-link>
            </ul>
        </nav>

        <div class="flow">
            @if (request()->localizedRouteIs('engagements.show'))
                <h2>{{ __('Overview') }}</h2>
                @can('update', $project)
                    <p><a class="button"
                            href="{{ localized_route('engagements.edit', $engagement) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('overview') . '</span>']) !!}</a>
                    </p>
                @endcan
                @include('engagements.partials.overview', ['level' => 3])
            @endif
        </div>
    </div>

</x-app-wide-layout>
