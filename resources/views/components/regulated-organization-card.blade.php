<article class="box card stack regulated-organization">
    <x-heading class="h4" :level="$level"><a
            href="{{ localized_route('regulated-organizations.show', $regulatedOrganization) }}">{{ $regulatedOrganization->name }}</a>
    </x-heading>
    <p>{{ __('Federally regulated organization') }}</p>
</article>
