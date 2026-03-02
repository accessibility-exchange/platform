@component('mail::message')
<p>@lang('mail.greeting'),</p>

<p>{{ __('A new member has joined an organization on The Accessibility Exchange.') }}</p>

<ul>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Name:'), 'value' => $memberName . ' (' . $memberRole . ')']) }}</li>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Organization name:'), 'value' => $organizationName]) }}</li>
    <li>{{ safe_markdown('**:label** :value', ['label' => __('Email:'), 'value' => $memberEmail]) }}</li>
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
