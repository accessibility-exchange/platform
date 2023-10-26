<div class="alert alert--prompt stack" x-data="{ visible: true }" x-show="visible" x-transition:leave.duration.500ms>
    <x-heading :level="$level">{{ $heading }}</x-heading>
    <p>{{ $description }}</p>
    <div class="actions">
        <a class="cta" href="{{ $actionUrl }}">{{ $actionLabel }}</a>
        <form wire:submit="dismiss">
            <button class="borderless" @click="visible = false">{{ __('Dismiss') }}</button>
        </form>
    </div>
</div>
