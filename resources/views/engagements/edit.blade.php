
<x-app-wide-layout>
    <x-slot name="title">{{ __('Edit engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $engagement->project) }}">{{ $engagement->project->name }}</a></li>
            <li><a href="{{ localized_route('engagements.show', ['project' => $project, 'engagement' => $engagement]) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1>
            {{ __('Edit engagement details') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]) }}" method="POST" novalidate>
        @csrf
        @method('put')

        <h2>{{ __('Name') }}</h2>

        <x-translatable-input name="name" :label="__('What is the name of your engagement?')" :model="$engagement" />

        <h2>{{ __('Description') }}</h2>

        <x-translatable-textarea name="description" :label="__('Please describe this engagement.')" :hint="__('This can include goals of your engagement, what topics you’ll cover, and what you’ll be asking participants to do.')" :model="$engagement" />

        <h2>{{ __('Sign up deadline') }}</h2>

        <div class="field @error('signup_by_date') field--error @enderror">
            <livewire:date-picker name="signup_by_date" :label="__('Please respond to your invitation to participate by:')" :value="old('signup_by_date', $engagement->signup_by_date?->format('Y-m-d') ?? null)" />
        </div>

        <button class="w-1/2">{{ __('Save') }}</button>
    </form>
</x-app-wide-layout>
