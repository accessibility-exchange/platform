<p class="h3">
    {{ __('Have more questions?') }}<br />
    {{ __('Call our support line at :number', ['number' => phone(settings('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA')]) }}
</p>
<x-interpretation class="interpretation--center" name="{{ __('Have more questions?', [], 'en') }}" namespace="questions" />
