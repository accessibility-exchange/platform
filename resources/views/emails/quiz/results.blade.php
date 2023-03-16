<x-mail::message>
    # {{ __('Quiz results') }}

    {{ __('Congratulations, :name!', ['name' => $name]) }}
    {{ __('You have successfully completed course :course and passed the quiz.', ['course' => $course]) }}

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
