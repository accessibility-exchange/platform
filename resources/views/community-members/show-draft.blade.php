<x-app-wide-layout>
    <x-slot name="title">{{ __('My community member page (draft)') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('My community member page (draft)') }}
        </h1>

    </x-slot>

    <h2>{{ __('Preview') }}</h2>
    <div class="preview flow">
        <div class="meta">
            @if($communityMember->getMedia('picture')->first())
            <img class="float-left" src="{{ $communityMember->getMedia('picture')->first()->getUrl('thumb') }}" alt="{{ $communityMember->picture_alt }}" />
            @else
            <img class="float-left" src="https://source.boringavatars.com/bauhaus/192/{{ $communityMember->name }}?colors=264653,2a9d8f,e9c46a,f4a261,e76f51" alt="{{ $communityMember->name }}" />
            @endif
            <h3>{{ $communityMember->name }}</h3>
            <p>{{ __('Individual community member') }}</p>
            <p>{{ $communityMember->locality }}, {{ get_region_name($communityMember->region, ["CA"], locale()) }}</p>
            @if($communityMember->pronouns)
            <p>{{ $communityMember->pronouns }}</p>
            @endif
        </div>
        <div class="flow" id="about">
            <h3>{{ __('About :name', ['name' => $communityMember->firstName()]) }}</h3>
            @include('community-members.partials.about', ['level' => 4])
        </div>
        <div class="flow" id="interests">
            <h3>{{ __('Interests') }}</h3>
            @include('community-members.partials.interests', ['level' => 4])
        </div>
        <div class="flow" id="experiences">
            <h3>{{ __('Experiences') }}</h3>
            @include('community-members.partials.experiences', ['level' => 4])
        </div>
        <div class="flow" id="access-needs">
            <h3>{{ __('Access needs') }}</h3>
            @include('community-members.partials.access-needs', ['level' => 4])
        </div>
    </div>
    <div class="steps flow">
        <h2>Steps to publish</h2>

        <ol class="progress flow">
            <li>
                <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember]) }}">{{ __('About you') }}</a>
            </li>
            <li>
                <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]) }}">{{ __('Interests') }}</a>
            </li>
            <li>
                <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]) }}">{{ __('Experiences') }}</a>
            </li>
            <li>
                <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]) }}">{{ __('Access needs') }}</a>
            </li>
        </ol>


        @can('update', $communityMember)
        @if($communityMember->checkStatus('draft'))
        <form action="{{ localized_route('community-members.update-publication-status', $communityMember) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-input type="submit" name="publish" :value="__('Publish my page')" />
        </form>
        @endif
        @endcan
    </div>
</x-app-wide-layout>
