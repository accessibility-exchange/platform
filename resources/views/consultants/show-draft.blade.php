<x-app-wide-layout>
    <x-slot name="title">{{ __('consultant.draft_title') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('consultant.draft_title') }}
        </h1>

    </x-slot>

    <h2>Preview</h2>
    <div class="preview flow">
        <div class="meta">
            <img class="float-left" src="https://source.boringavatars.com/bauhaus/192/?colors=264653,2a9d8f,e9c46a,f4a261,e76f51" alt="{{ $consultant->name }}" />
            <h3>{{ $consultant->name }}</h3>
            <p>{{ __('consultant.role_individual_consultant') }}</p>
            <p>{{ $consultant->locality }}, {{ get_region_name($consultant->region, ["CA"], locale()) }}</p>
            @if($consultant->pronouns)
            <p>{{ $consultant->pronouns }}</p>
            @endif
        </div>
        <div class="flow" id="about">
            <h3>{{ __('consultant.section_about_person', ['name' => $consultant->firstName()]) }}</h3>
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="{{ localized_route('consultants.edit', $consultant) }}">{!! __('consultant.edit_section', ['section' => '<span class="visually-hidden">' . __('consultant.section_about') . '</span>']) !!}</a></p>

            {!! Illuminate\Mail\Markdown::parse($consultant->bio) !!}

            @if($consultant->links)
            <h4>{{ $consultant->firstName() }}’s links</h4>
            <ul>
                @foreach($consultant->links as $link)
                <li><a href="{{ $link['url'] }}" rel="external">{{ $link['text'] }}</a></li>
                @endforeach
            </ul>
            @endif

            @if($consultant->creator === 'other')
            <p><em>{{ __('This page was created by :creator, :name’s :relationship.', ['creator' => $consultant->creator_name, 'name' => $consultant->firstName(), 'relationship' => $consultant->creator_relationship]) }}</em></p>
            @endif
        </div>
        <div class="flow" id="interests-and-goals">
            <h3>{{ __('consultant.section_interests_and_goals') }}</h3>
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('consultant.edit_section', ['section' => '<span class="visually-hidden">' . __('consultant.section_interests_and_goals') . '</span>']) !!}</a></p>

            @include('consultants.boilerplate.interests-and-goals', ['level' => 4])
        </div>
        <div class="flow" id="lived-experience">
            <h3>{{ __('consultant.section_lived_experience') }}</h3>
            <x-privacy-indicator level="private">
                <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('consultant.edit_section', ['section' => '<span class="visually-hidden">' . __('consultant.section_lived_experience') . '</span>']) !!}</a></p>

            @include('consultants.boilerplate.lived-experience', ['level' => 4])
        </div>
        <div class="flow" id="professional-experience">
            <h3>{{ __('consultant.section_professional_experience') }}</h3>
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('consultant.edit_section', ['section' => '<span class="visually-hidden">' . __('consultant.section_professional_experience') . '</span>']) !!}</a></p>

            @include('consultants.boilerplate.professional-experience', ['level' => 4])
        </div>
        <div class="flow" id="access-needs">
            <h3>{{ __('consultant.section_access_needs') }}</h3>
            <x-privacy-indicator level="private">
                <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('consultant.edit_section', ['section' => '<span class="visually-hidden">' . __('consultant.section_access_needs') . '</span>']) !!}</a></p>

            @include('consultants.boilerplate.access-needs', ['level' => 4])
        </div>
    </div>
    <div class="steps flow">
        <h2>Steps to publish</h2>

        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('consultants.edit', $consultant) }}">{{ __('consultant.section_interests_and_goals') }}</a><br />
            <small>Completed</small>
        </p>
        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('consultants.edit', $consultant) }}">{{ __('consultant.section_lived_experience') }}</a><br />
            <small>Completed</small>
        </p>
        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('consultants.edit', $consultant) }}">{{ __('consultant.section_access_needs') }}</a><br />
            <small>Completed</small>
        </p>

        <h3>Optional:</h3>

        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('consultants.edit', $consultant) }}">{{ __('consultant.section_professional_experience') }}</a><br />
            <small>Completed</small>
        </p>

        @can('update', $consultant)
        @if($consultant->checkStatus('draft'))
        <form action="{{ localized_route('consultants.update-status', $consultant) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-input type="submit" name="publish" :value="__('consultant.action_publish')" />
        </form>
        @endif
        @endcan
    </div>
</x-app-wide-layout>
