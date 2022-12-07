<x-mail::message>
    # {{ __('Quiz results') }}

    {{ __('Congraturations') . ', ' . $name . '!' }}
    {{ __('You have successfully completed course') . ' ' . $course . ' ' . __('and passed the quiz') . '.' }}

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
