@props(['errors'])

@if ($errors->any())
    <div {{ $attributes }}>
        <p class="center">{{ __('auth.error_intro') }}</p>

        <ul role="list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
