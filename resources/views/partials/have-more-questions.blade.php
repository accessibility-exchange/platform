<p class="h3">
    {{ __('Have more questions?') }}<br />
    {{ __('Call our support line at :number', ['number' => phone(settings('phone'), 'CA')->formatForCountry('CA')]) }}
</p>
<x-interpretation class="interpretation--center" name="{{ __('Have more questions?', [], 'en') }}" namespace="questions" />
