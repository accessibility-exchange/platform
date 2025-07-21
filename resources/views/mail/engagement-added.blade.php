@component('mail::message')
{{ __('A new engagement has been uploaded on The Accessibility Exchange:') }}
{{
    safe_markdown('[:engagement](:engagement_url) by [:projectable](:projectable_url)', [
        'engagement' => $engagement->getTranslation('name', locale()),
        'engagement_url' => localized_route('engagements.show', $engagement),
        'projectable' => $projectable->getTranslation('name', locale()),
        'projectable_url' => localized_route($projectable->getRoutePrefix().'.show', $projectable),
    ])
}}

<div>{{ __('Check it out.') }}</div>
@component('mail::button', ['url' => localized_route('engagements.show', $engagement)])
{{ __('Go to new engagement') }}
@endcomponent

{{ safe_markdown('They have been instructed to send their signed agreement to <:email>.', ['email' => settings_localized('email', locale())]) }}
@endcomponent
