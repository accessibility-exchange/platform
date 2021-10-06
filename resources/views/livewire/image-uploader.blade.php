<div class="flow">
    @if($image instanceof \Spatie\MediaLibrary\MediaCollections\Models\Media)
    <img src="{{ $image->getUrl('thumb') }}" alt="{{ $alt }}" />
    <button type="button" wire:click.prevent="remove">{{ __('Remove image') }}</button>
    @elseif($image instanceof \Livewire\TemporaryUploadedFile)
    <img src="{{ $image->temporaryUrl() }}" alt="{{ $alt }}" />
    @endif
    <input wire:model="image" type="file" id="{{ $name }}" name="{{ $name }}" aria-describedby="{{ $name }}-hint" />
</div>
