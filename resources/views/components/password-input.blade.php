@props([
    'name' => 'password',
    'autocomplete' => false
])

<div class="password" x-data="{show: false}">
    <input name="{{ $name }}" id="{{ $id ?? $name }}" x-bind:type="show ? 'text' : 'password'" required {!! $autocomplete ? 'autocomplete="' . $autocomplete . '"' : '' !!} />
    <div class="field">
        <x-hearth-checkbox :name="'show_' . $name" x-model="show" />
        <x-hearth-label :for="'show_' . $name" :value="__('Show password')" />
    </div>
</div>
