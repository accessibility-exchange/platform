@if(Session::has('success'))
    <x-alert type="success">
        {{ Session::get('success') }}
    </x-alert>
@endif
