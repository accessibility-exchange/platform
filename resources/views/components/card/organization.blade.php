@props([
    'level' => 2,
    'model' => null,
])

<x-card class="organization" title-class="h4">
    <x-slot name="title"><a href="{{ localized_route('organizations.show', $model) }}">{{ $model->name }}</a></x-slot>
    <p>
        <strong>{{ App\Enums\OrganizationType::labels()[$model->type] }}</strong>
        @if ($model->display_roles)
            <br />
            @foreach ($model->display_roles as $role)
                <span class="font-semibold">{{ $role }}</span>
                @if (!$loop->last)
                    <br />
                @endif
            @endforeach
        @endif
    </p>

    <p><span class="font-semibold">{{ __('Location') }}:</span> {{ $model->locality }},
        {{ $model->display_region }}</p>
</x-card>
