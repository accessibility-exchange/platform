@component('mail::message')
{{
    safe_markdown('You left [:engagement](:engagement_url) by [:projectable](:projectable_url).', [
        'engagement' => $engagement->getTranslation('name', locale()),
        'engagement_url' => localized_route('engagements.show', $engagement),
        'projectable' => $projectable->getTranslation('name', locale()),
        'projectable_url' => localized_route($projectable->getRoutePrefix().'.show', $projectable),
    ])
}}

@component('mail::button', ['url' => localized_route('engagements.show', $engagement)])
{{ __('View engagement details') }}
@endcomponent
@endcomponent
