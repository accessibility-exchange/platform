<h2 class="text-center" id="experiences">
    {{ __('Who can be a :role?', ['role' => __('Consultation Participant')]) }}</h2>
<x-interpretation name="{{ __('Who can be a :role?', ['role' => __('Consultation Participant', [], 'en')], 'en') }}"
    namespace="consultation_participants" />
<div class="stack flex h-full flex-col items-center justify-center">
    <p>{{ __('Any of the following could be Consultation Participants:') }}
    <ul class="flex w-1/2 flex-col">
        <li class="mx-auto">{{ 'persons with disabilities' }}</li>
        <li class="mx-auto">{{ 'Deaf persons' }}</li>
        <li class="mx-auto">{{ 'their supporters' }}</li>
        <li class="mx-auto">{{ 'persons representing Disability organizations' }}</li>
        <li class="mx-auto">{{ 'Disability support organizations' }}</li>
        <li class="mx-auto">{{ 'broader civil society organizations' }}</li>
    </ul>
    </p>
</div>
