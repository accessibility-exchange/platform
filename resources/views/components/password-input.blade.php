<div class="password" x-data="{show: false}">
    <input type="password" x-bind:type="show ? 'text' : 'password'"
        {!! $attributes->merge([
            'name' => $name,
            'id' => $id,
        ]) !!}
        {{ $required ? 'required' : '' }}
        @disabled($disabled)
        {!! $describedBy() ? 'aria-describedby="' . $describedBy() . '"' : '' !!}
        {!! $invalid ? 'aria-invalid="true"' : '' !!}
    >
    <div class="field">
        <x-hearth-checkbox :name="'show_' . $name" x-model="show" />
        <x-hearth-label :for="'show_' . $name" :value="__('Show password')" />
    </div>
</div>


