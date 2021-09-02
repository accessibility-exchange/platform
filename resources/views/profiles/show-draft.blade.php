<x-app-wide-layout>
    <x-slot name="header">
        <h1>
            {{ __('profile.draft_title') }}
        </h1>

    </x-slot>

    <h2>Preview</h2>
    <div class="preview flow">
        <div class="meta">
            <img class="float-left" src="https://source.boringavatars.com/bauhaus/192/?colors=264653,2a9d8f,e9c46a,f4a261,e76f51" alt="{{ $profile->name }}" />
            <h3>{{ $profile->name }}</h3>
            <p>{{ __('profile.role_individual_consultant') }}</p>
            <p>{{ $profile->locality }}, {{ get_region_name($profile->region, ["CA"], locale()) }}</p>
            @if($profile->pronouns)
            <p>{{ $profile->pronouns }}</p>
            @endif
            @if($profile->birth_date)
            <p>{{ __('profile.age', ['years' => $profile->age()]) }}</p>
            @endif
        </div>
        <div class="flow" id="about">
            <h3>{{ __('profile.section_about_person', ['name' => $profile->firstName()]) }}</h3>

            <p><a class="button" href="{{ localized_route('profiles.edit', $profile) }}">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_about') . '</span>']) !!}</a></p>

            {!! Illuminate\Mail\Markdown::parse($profile->bio) !!}
        </div>
        <div class="flow" id="interests-and-goals">
            <h3>{{ __('profile.section_interests_and_goals') }}</h3>

            <p><a class="button" href="#">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_interests_and_goals') . '</span>']) !!}</a></p>

            @include('profiles.boilerplate.interests-and-goals', ['level' => 4])
        </div>
        <div class="flow" id="lived-experience">
            <h3>{{ __('profile.section_lived_experience') }}</h3>

            <p><a class="button" href="#">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_lived_experience') . '</span>']) !!}</a></p>

            @include('profiles.boilerplate.lived-experience', ['level' => 4])
        </div>
        <div class="flow" id="professional-experience">
            <h3>{{ __('profile.section_professional_experience') }}</h3>

            <p><a class="button" href="#">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_professional_experience') . '</span>']) !!}</a></p>

            @include('profiles.boilerplate.professional-experience', ['level' => 4])
        </div>
        <div class="flow" id="access-needs">
            <h3>{{ __('profile.section_access_needs') }}</h3>

            <p><a class="button" href="#">{!! __('profile.edit_section', ['section' => '<span class="visually-hidden">' . __('profile.section_access_needs') . '</span>']) !!}</a></p>

            @include('profiles.boilerplate.access-needs', ['level' => 4])
        </div>
    </div>
    <div class="steps flow">
        <h2>Steps to publish</h2>

        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('profiles.edit', $profile) }}">{{ __('profile.section_interests_and_goals') }}</a><br />
            <small>Completed</small>
        </p>
        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('profiles.edit', $profile) }}">{{ __('profile.section_lived_experience') }}</a><br />
            <small>Completed</small>
        </p>
        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('profiles.edit', $profile) }}">{{ __('profile.section_access_needs') }}</a><br />
            <small>Completed</small>
        </p>

        <h3>Optional:</h3>

        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('profiles.edit', $profile) }}">{{ __('profile.section_professional_experience') }}</a><br />
            <small>Completed</small>
        </p>

        @can('update', $profile)
        @if($profile->status == 'draft')
        <form action="{{ localized_route('profiles.update-status', $profile) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-input type="submit" name="publish" :value="__('profile.action_publish')" />
        </form>
        @endif
        @endcan
    </div>
</x-app-wide-layout>
