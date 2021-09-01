<x-app-wide-layout>
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
        @can('update', $profile)
        @if($profile->status == 'published')
        <form action="{{ localized_route('profiles.update-status', $profile) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-input type="submit" name="unpublish" :value="__('profile.action_unpublish')" />
        </form>
        @endif
        @endcan
    </x-slot>

    <div class="tabs flow" x-data="tabs(window.location.hash ? window.location.hash.substring(1) : 'about')" x-on:resize.window="enabled = window.innerWidth > 1023">
        <h2 x-show="!enabled">{{ __('Contents') }}</h2>
        <ul x-bind="tabList">
            <li x-bind="tabWrapper"><a href="#about" x-bind="tab">{{ __('profile.section_about') }}</a></li>
            <li x-bind="tabWrapper"><a href="#interests-and-goals" x-bind="tab">{{ __('profile.section_interests_and_goals') }}</a></li>
            <li x-bind="tabWrapper"><a href="#lived-experience" x-bind="tab">{{ __('profile.section_lived_experience') }}</a></li>
            <li x-bind="tabWrapper"><a href="#professional-experience" x-bind="tab">{{ __('profile.section_professional_experience') }}</a></li>
            <li x-bind="tabWrapper"><a href="#access-needs" x-bind="tab">{{ __('profile.section_access_needs') }}</a></li>
        </ul>
        <div class="flow" id="about" x-bind="tabpanel">
            <h2>{{ __('profile.section_about_person', ['name' => $profile->firstName()]) }}</h2>
            @can('update', $profile)
            <p><a class="button" href="{{ localized_route('profiles.edit', $profile) }}">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_about') . '</span>']) !!}</a></p>
            @endcan

            {!! Illuminate\Mail\Markdown::parse($profile->bio) !!}
        </div>
        <div class="flow" id="interests-and-goals" x-bind="tabpanel">
            <h2>{{ __('profile.section_interests_and_goals') }}</h2>
            @can('update', $profile)
            <p><a class="button" href="#">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_interests_and_goals') . '</span>']) !!}</a></p>
            @endcan
            @include('profiles.boilerplate.interests-and-goals', ['level' => 3])
        </div>
        <div class="flow" id="lived-experience" x-bind="tabpanel">
            <h2>{{ __('profile.section_lived_experience') }}</h2>
            @can('update', $profile)
            <p><a class="button" href="#">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_lived_experience') . '</span>']) !!}</a></p>
            @endcan
            @include('profiles.boilerplate.lived-experience', ['level' => 3])
        </div>
        <div class="flow" id="professional-experience" x-bind="tabpanel">
            <h2>{{ __('profile.section_professional_experience') }}</h2>
            @can('update', $profile)
            <p><a class="button" href="#">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_professional_experience') . '</span>']) !!}</a></p>
            @endcan
            @include('profiles.boilerplate.professional-experience', ['level' => 3])
        </div>
        <div class="flow" id="access-needs" x-bind="tabpanel">
            <h2>{{ __('profile.section_access_needs') }}</h2>
            @can('update', $profile)
            <p><a class="button" href="#">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_access_needs') . '</span>']) !!}</a></p>
            @endcan
            @include('profiles.boilerplate.access-needs', ['level' => 3])
        </div>
    </div>
</x-app-wide-layout>
