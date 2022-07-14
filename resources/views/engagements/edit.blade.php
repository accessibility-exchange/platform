
<x-app-layout>
    <x-slot name="title">{{ __('Edit engagement') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit engagement') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form id="edit-engagement" action="{{ localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]) }}" method="POST" novalidate>
        @csrf
        @method('put')

        <x-translatable-input name="name" :label="__('Engagement name')" :model="$engagement" />

        <x-translatable-textarea name="goals" :label="__('Goals for engagement')" :model="$engagement" />

        <fieldset>
            <legend>{{ __('Recruiting participants') }}</legend>

            <x-hearth-hint for="recruitment">{{ __('How do you want to recruit participants for this engagement?') }}</x-hearth-hint>

            <x-hearth-radio-buttons name="recruitment" :options="[
                ['value' => 'automatic', 'label' => __('With the Accessibility Exchange’s automatic matching system')],
                ['value' => 'open', 'label' => __('With an open call for participants')],
            ]" :checked="old('recruitment', $engagement->recruitment) ?? false" hinted />
        </fieldset>

        <button>{{ __('Update engagement') }}</button>
    </form>
</x-app-layout>
