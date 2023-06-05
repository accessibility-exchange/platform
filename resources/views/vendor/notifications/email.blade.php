@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
<h1>{{ $greeting }}</h1>
@else
@if ($level === 'error')
<h1>@lang('mail.error')</h1>
@else
<h1>@lang('mail.greeting')</h1>
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('mail.salutation'),<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    'mail.link_guidance',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all"><a href="{{ $actionUrl }}">{{ $displayableActionUrl }}</a></span>
@endslot
@endisset
@endcomponent
