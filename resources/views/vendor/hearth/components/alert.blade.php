<div class="alert alert--{{ $type }} stack" {{ $attributes }} x-show="visible"
    x-transition:leave.duration.500ms>
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
        </div>
