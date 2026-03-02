@component('mail::message')
<p>@lang('mail.greeting'),</p>

<p>{{ __('A new user has registered on The Accessibility Exchange.') }}</p>

<ul>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Name:'), 'value' => $userName]) }}</li>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Email:'), 'value' => $userEmail]) }}</li>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Account type:'), 'value' => $contextLabel]) }}</li>
</ul>

@component('mail::button', ['url' => localized_route('dashboard')])
{{ __('Dashboard') }}
@endcomponent

<p>
@lang('mail.salutation'),<br>
{{ config('app.name') }}
</p>
@endcomponent
