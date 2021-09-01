<div class="alert alert--{{ $type }} flow" role="alert">
    <p class="title">@switch($type)
        @case('error')
            <x-heroicon-s-exclamation-circle class="icon" />
            @break
        @case('warning')
            <x-heroicon-s-exclamation class="icon" />
            @break
        @case('success')
            <x-heroicon-s-check-circle class="icon" />
            @break
        @default
        <x-heroicon-s-information-circle class="icon" />
    @endswitch{{ $title }}</p>
    {{ $slot }}
</div>
