<x-card class="individual" title-class="h4">
    <x-slot name="title"><a href="{{ localized_route('individuals.show', $model) }}">{{ $model->name }}</a></x-slot>
    <p>
        @foreach ($model->display_roles as $role)
            <strong>{{ $role }}</strong>
            @if (!$loop->last)
                <br />
            @endif
        @endforeach
    </p>
    <p><span class="font-semibold">{{ __('Location') }}:</span> {{ $model->locality }},
        {{ $model->display_region }}</p>
</x-card>
