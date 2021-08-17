<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('dashboard.your_title', ['name' => Auth::user()->name]) }}</h1>
    </x-slot>

    {{-- <p>{{ __('dashboard.welcome', ['name' => Auth::user()->name]) }}</p>

    <p>
        <a href="{{ localized_route('profiles.index') }}">{{ __('profile.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p>

    <p>
        <a href="{{ localized_route('organizations.index') }}">{{ __('organization.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p>

    <p>
        <a href="{{ localized_route('entities.index') }}">{{ __('entity.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p> --}}


    @if(!Auth::user()->profile)

    <p>{{ __('Here are a few things to do before you can start consulting.') }}</p>

    <h2><a href="{{ localized_route('profiles.create') }}">{{ __('Create your consultant page') }}</a></h2>

    <p>{{ __('Tell us a little bit about yourself, so we can match you with an organization that suits you and your preferences.') }}</p>

    @else
    <p>
        <a href="{{ localized_route('profiles.show', ['profile' => Auth::user()->profile]) }}"><strong>{{ Auth::user()->profile->name }}</strong></a><br />
        <a href="{{ localized_route('profiles.edit', ['profile' => Auth::user()->profile]) }}">{{ __('profile.edit_profile') }}</a>
    </p>
    @endif

    {{-- <h2><a href="{{ localized_route('resources.index') }}">{{ __('Learn about consulting') }}</a></h2>

    <p>{{ __('Learn what you need to provide a great consulting service.') }}</p>

    <h2>{{ __('user.organizations_title') }}</h2>

    @forelse(Auth::user()->organizations as $organization)
    <p>
        <a href="{{ localized_route('organizations.show', $organization) }}"><strong>{{ $organization->name }}</strong></a><br />
        @if(Auth::user()->isAdministratorOf($organization))
        <a href="{{ localized_route('organizations.edit', $organization) }}">{{ __('organization.edit_title') }}</a>
        @endif
    </p>
    @empty
    <p>{!! __('user.no_organization', ['create_link' => '<a href="' . localized_route('organizations.create') . '">' . __('user.create_organization') . '</a>']) !!}</p>
    @endforelse

    <h2>{{ __('user.entities_title') }}</h2>

    @forelse(Auth::user()->entities as $entity)
    <p>
        <a href="{{ localized_route('entities.show', $entity) }}"><strong>{{ $entity->name }}</strong></a><br />
        @if(Auth::user()->isAdministratorOf($entity))
        <a href="{{ localized_route('entities.edit', $entity) }}">{{ __('entity.edit_title') }}</a>
        @endif
    </p>
    @empty
    <p>{!! __('user.no_entity', ['create_link' => '<a href="' . localized_route('entities.create') . '">' . __('user.create_entity') . '</a>']) !!}</p>
    @endforelse --}}

    <x-slot name="aside">
        <h2>{{ __('Need some support?') }}</h2>
        <ul role="list">
            <li><a href="#">{{ __('Call the support line') }}</a></li>
            <li><a href="#">{{ __('E-mail us') }}</a></li>
        </ul>
    </x-slot>
</x-app-layout>
