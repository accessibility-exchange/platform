<nav class="secondary" aria-labelledby="what-we-ask-for">
    <ul role="list">
        <li>
            <x-nav-link :href="localized_route('about.individual-consultation-participants-what-we-ask-for')" :active="request()->localizedRouteIs('about.individual-consultation-participants-what-we-ask-for')">{{ __('Consultation Participants — Individual') }}
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="localized_route('about.individual-accessibility-consultants-what-we-ask-for')" :active="request()->localizedRouteIs('about.individual-accessibility-consultants-what-we-ask-for')">{{ __('Accessibility Consultants — Individual') }}
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="localized_route('about.individual-community-connectors-what-we-ask-for')" :active="request()->localizedRouteIs('about.individual-community-connectors-what-we-ask-for')">{{ __('Community Connectors — Individual') }}</x-nav-link>
        </li>
    </ul>
</nav>
