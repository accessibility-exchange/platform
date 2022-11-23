<x-mail::message>
    # Introduction

    <p>{{ __('To whom it may concern,') }}
    <p>
    <p>{{ __('We would like to inform you that ') }} . $name . {{ __('has passed quiz') }}</p>

    <x-mail::button :url="''">
        Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
