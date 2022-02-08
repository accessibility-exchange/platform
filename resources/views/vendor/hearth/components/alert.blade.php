<div class="alert alert--{{ $type }} flow">
    <p class="title">@switch($type)
        @case('error')
            <x-heroicon-o-x-circle class="icon" />
            @break
        @case('warning')
            <x-heroicon-o-exclamation-circle class="icon" />
            @break
        @case('success')
            <x-heroicon-o-check-circle class="icon" />
            @break
        @default
            <x-heroicon-o-information-circle class="icon" />
    @endswitch{{ $title }}</p>
    {{ $slot }}
</div>
