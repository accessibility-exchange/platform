@props([
    'dismissable' => true,
])

<div class="alert alert--{{ $type }} stack" {{ $attributes }}
    x-show="@if (in_array($type, ['error', 'warning'])) true @else visible @endif" x-transition:leave.duration.500ms>
    <p class="title">
        @switch($type)
            @case('error')
                @svg('heroicon-o-x-circle')
            @break

            @case('warning')
                @svg('heroicon-o-exclamation-circle')
            @break

            @case('success')
                @svg('heroicon-o-check-circle')
            @break

            @default
                @svg('heroicon-o-information-circle')
                @endswitch{{ $title }}
            </p>
            {{ $slot }}

            @if ($actions ?? '' || $dismissable)
                <div class="actions">
                    {{ $actions ?? '' }}
                    @if (!in_array($type, ['error', 'warning']) && $dismissable !== false)
                        <button class="borderless" type="button" @click="visible = false">{{ __('Dismiss') }}</button>
                    @endif
                </div>
            @endif
        </div>
