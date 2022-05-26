<article class="box card stack organization">
    <x-heading class="h4" :level="$level"><a href="{{ localized_route('organizations.show', $organization) }}">{{ $organization->name }}</a></x-heading>
    <p>{{ __('Organization') }}</p>
</article>
