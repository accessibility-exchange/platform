@props([
    'level' => 2,
    'model' => null,
])

<x-card class="regulated-organization" title-class="h4">
    <x-slot name="title"><a href="{{ localized_route('regulated-organizations.show', $model) }}">{{ $model->name }}</a>
    </x-slot>
    <p><strong>{{ __('Federally regulated organization') }}</strong></p>
    <p>
        <span class="font-semibold">{{ __('Sector:') }}</span>
        {{ implode(', ',$model->sectors()->pluck('name')->toArray()) }}<br />
        <span class="font-semibold">{{ __('Location') }}:</span> {{ $model->locality }},
        {{ $model->display_region }}
    </p>
</x-card>
