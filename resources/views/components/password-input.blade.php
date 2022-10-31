<div class="password" x-data="{ show: false }">
    <input type="password" x-bind:type="show ? 'text' : 'password'" {!! $attributes->merge([
        'name' => $name,
        'id' => $id,
    ]) !!}
        {{ $required ? 'required' : '' }} @disabled($disabled) {!! $describedBy() ? 'aria-describedby="' . $describedBy() . '"' : '' !!} {!! $invalid ? 'aria-invalid="true"' : '' !!}>
    <div class="field">
        <x-hearth-checkbox :disabled="$disabled" :name="'show_' . $name" x-model="show" />
        <x-hearth-label class="inline-flex items-center" :for="'show_' . $name">{{ __('Show password') }} @svg('heroicon-o-eye', 'ml-2 icon--lg')
        </x-hearth-label>
    </div>
</div>
