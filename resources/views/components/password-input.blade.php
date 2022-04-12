@props([
    'name' => 'password',
    'autocomplete' => false
])

<div class="password repel" x-data="{show: false}">
    <input name="{{ $name }}" id="{{ $id ?? $name }}" x-bind:type="show ? 'text' : 'password'" required {!! $autocomplete ? 'autocomplete="' . $autocomplete . '"' : '' !!} />
    <button type="button" x-on:click="show = !show" x-bind:aria-pressed="show">
        <x-heroicon-o-eye x-show="!show" />
        <x-heroicon-o-eye-off x-show="show" x-cloak />
        <span x-show="!show" />{{ __('Show') }}</span>
        <span x-show="show" x-cloak />{{ __('Hide') }}</span>
    </button>
</div>
