@props([
    'dismissable' => true,
])

<div class="alert alert--{{ $type }} stack" {{ $attributes }}
    x-show="@if (in_array($type, ['error', 'warning'])) true @else visible @endif" x-transition:leave.duration.500ms>
    <p class="title">
        @switch($type)
            @case('error')
                <x-heroicon-o-x-circle />
            @break

            @case('warning')
                <x-heroicon-o-exclamation-circle />
            @break

            @case('success')
                <x-heroicon-o-check-circle />
            @break

            @default
                <x-heroicon-o-information-circle />
                @endswitch{{ $title }}
            </p>
            {{ $slot }}

            <div class="flex gap-2 px-1">
                {{ $actions ?? '' }}
                @if (!in_array($type, ['error', 'warning']) && $dismissable !== false)
                    <button class="borderless" type="button" @click="visible = false">
                        {{ __('Dismiss') }}
                    </button>
                @endif
            </div>
        </div>
