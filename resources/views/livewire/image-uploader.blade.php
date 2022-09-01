<div class="stack">
    @if ($image instanceof \Spatie\MediaLibrary\MediaCollections\Models\Media)
        <img src="{{ $image->getUrl('thumb') }}" @if ($alt) alt="{{ $alt }}" @endif />
        <button type="button" wire:click.prevent="remove">{{ __('Remove image') }}</button>
    @elseif($image instanceof \Livewire\TemporaryUploadedFile)
        <img src="{{ $image->temporaryUrl() }}" @if ($alt) alt="{{ $alt }}" @endif />
    @endif
    <input id="{{ $name }}" name="{{ $name }}" type="file" aria-describedby="{{ $name }}-hint"
        wire:model="image" />
</div>
