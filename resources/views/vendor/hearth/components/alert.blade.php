<div class="alert alert--{{ $type }} flow" role="alert">
    <p class="title">@switch($type)
        @case('error')
            <x-heroicon-s-exclamation-circle style="display: inline-block; margin-right: 0.25em; margin-bottom: -0.125em; width: 1em; height: 1em;" />
            @break
        @default
        <x-heroicon-s-information-circle style="display: inline-block; margin-right: 0.25em; margin-bottom: -0.125em; width: 1em; height: 1em;" />
    @endswitch{{ $title }}</p>
    {{ $slot }}
</div>
