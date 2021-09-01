<x-app-tabbed-layout>
    <x-slot name="header">
        <img class="float-left" src="https://source.boringavatars.com/bauhaus/192/?colors=264653,2a9d8f,e9c46a,f4a261,e76f51" alt="{{ $profile->name }}" />
        <h1>
            {{ $profile->name }}
        </h1>
        <div class="meta">
            <p>{{ __('profile.role_individual_consultant') }}</p>
            <p>{{ $profile->locality }}, {{ get_region_name($profile->region, ["CA"], locale()) }}</p>
            @if($profile->pronouns)
            <p>{{ $profile->pronouns }}</p>
            @endif
            @if($profile->birth_date)
            <p>{{ __('profile.age', ['years' => $profile->age()]) }}</p>
            @endif
        </div>
        @if($profile->user_id === Auth::user()->id && $profile->status === 'published')
        <form action="{{ localized_route('profiles.unpublish', $profile) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-button type="submit">{{ __('profile.action_unpublish') }}</x-hearth-button>
        </form>
        @endif
    </x-slot>

    <div class="tabs flow" x-data="tabs(window.location.hash ? window.location.hash.substring(1) : 'about')" x-on:resize.window="enabled = window.innerWidth > 1023">
        <h2 x-show="!enabled">{{ __('Contents') }}</h2>
        <ul x-bind="tabList">
            <li x-bind="tabWrapper"><a href="#about" x-bind="tab">{{ __('About') }}</a></li>
            <li x-bind="tabWrapper"><a href="#interests-and-goals" x-bind="tab">{{ __('Interests and goals') }}</a></li>
            <li x-bind="tabWrapper"><a href="#lived-experience" x-bind="tab">{{ __('Lived experience') }}</a></li>
            <li x-bind="tabWrapper"><a href="#professional-experience" x-bind="tab">{{ __('Professional experience') }}</a></li>
            <li x-bind="tabWrapper"><a href="#access-needs" x-bind="tab">{{ __('Access needs') }}</a></li>
        </ul>
        <div id="about" x-bind="tabpanel">
            <h2>{{ __('profile.about_person', ['name' => $profile->firstName()]) }}</h2>

            {!! Illuminate\Mail\Markdown::parse($profile->bio) !!}
        </div>
        <div id="interests-and-goals" x-bind="tabpanel">
            <h2>{{ __('Interests and goals') }}</h2>
        </div>
        <div id="lived-experience" x-bind="tabpanel">
            <h2>{{ __('Lived experience') }}</h2>
        </div>
        <div id="professional-experience" x-bind="tabpanel">
            <h2>{{ __('Professional experience') }}</h2>
        </div>
        <div id="access-needs" x-bind="tabpanel">
            <h2>{{ __('Access needs') }}</h2>
        </div>
    </div>

    @can('update', $profile)
    <p><a href="{{ localized_route('profiles.edit', $profile) }}">{{ __('profile.edit_profile') }}</a></p>
    @endcan
</x-app-tabbed-layout>
