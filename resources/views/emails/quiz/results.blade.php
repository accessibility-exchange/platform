<x-mail::message>
    <h1>{{ __('Quiz results') }}</h1>

    {{ __('Congratulations, :name!', ['name' => $name]) }}
    {{ __('You have successfully completed course :course and passed the quiz.', ['course' => $course]) }}

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
