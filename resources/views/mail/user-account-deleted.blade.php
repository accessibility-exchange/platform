@component('mail::message')
<p>@lang('mail.greeting'),</p>

<p>{{ __('A user has deleted their account on The Accessibility Exchange.') }}</p>

<ul>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Name:'), 'value' => $userName]) }}</li>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Email:'), 'value' => $userEmail]) }}</li>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Account type:'), 'value' => $contextLabel]) }}</li>
    @if($organizationName)
        <li>{{ safe_markdown('**:label** :value', ['label' => __('Organization:'), 'value' => $organizationName]) }}</li>
    @endif
</ul>

<p>
@lang('mail.salutation'),<br>
{{ config('app.name') }}
</p>
@endcomponent
