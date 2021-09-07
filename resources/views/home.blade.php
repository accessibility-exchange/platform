<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ __('home.your_title', ['name' => Auth::user()->name]) }}</h1>
    </x-slot>

    {{-- <p>{{ __('home.welcome', ['name' => Auth::user()->name]) }}</p>

    <p>
        <a href="{{ localized_route('consultants.index') }}">{{ __('consultant.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p>

    <p>
        <a href="{{ localized_route('organizations.index') }}">{{ __('organization.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p>

    <p>
        <a href="{{ localized_route('entities.index') }}">{{ __('entity.browse_all') }} <span class="aria-hidden">&rarr;</span></a>
    </p> --}}


    @if(Auth::user()->context === 'consultant')
    @if(!Auth::user()->consultant)

    <p>{{ __('home.consultant_things_you_can_do') }}</p>

    <h2>{{ __('home.consultant_create_page_title') }}</h2>

    <p>{{ __('home.consultant_create_page_info') }}</p>

    <p>{!! __('home.create_page_prompt', ['link' => '<a href="' . localized_route('consultants.create') . '"><strong>' . __('consultant.singular_title_lower') . '</strong></a>']) !!}</p>

    @else
    <p>
        <a href="{{ localized_route('consultants.show', ['consultant' => Auth::user()->consultant]) }}"><strong>{{ Auth::user()->consultant->name }}</strong>@if(Auth::user()->consultant->checkStatus('draft')) ({{ __('consultant.status_draft') }})@endif</a><br />
        <a href="{{ localized_route('consultants.edit', ['consultant' => Auth::user()->consultant]) }}">{{ __('consultant.edit_consultant_page') }}</a>
    </p>
    @endif

    <h2>{{ __('home.consultant_learn_title') }}</h2>

    <p>{{ __('home.consultant_learn_info') }}</p>

    <p>{!! __('home.learn_prompt', ['link' => '<a href="' . localized_route('resources.index') . '"><strong>' . __('resource.plural') . '</strong></a>']) !!}</p>

    @elseif (Auth::user()->context === 'entity')

    <p>{{ __('home.entity_things_you_can_do') }}</p>

    <h2>{{ __('home.entity_create_page_title') }}</h2>

    <p>{{ __('home.entity_create_page_info') }}</p>

    <p>{!! __('home.create_page_prompt', ['link' => '<a href="' . localized_route('entities.create') . '"><strong>' . __('entity.singular_title_lower') . '</strong></a>']) !!}</p>

    <h2>{{ __('home.entity_learn_title') }}</h2>

    <p>{{ __('home.entity_learn_info') }}</p>

    <p>{!! __('home.learn_prompt', ['link' => '<a href="' . localized_route('resources.index') . '"><strong>' . __('resource.plural') . '</strong></a>']) !!}</p>

    @endif

    {{-- <h2>{{ __('user.organizations_title') }}</h2>

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

    {{-- <x-slot name="aside">
        <h2>{{ __('Need some support?') }}</h2>
        <ul role="list">
            <li><a href="#">{{ __('Call the support line') }}</a></li>
            <li><a href="#">{{ __('E-mail us') }}</a></li>
        </ul>
    </x-slot> --}}
</x-app-layout>
