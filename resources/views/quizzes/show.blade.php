<x-app-layout page-width="wide">
    <x-slot name="header">
    </x-slot>
    <div class="stack ml-2 mr-2">
        <x-slot name="title">{{ $title }}</x-slot>
    </div>
    <div class="stack counter ml-2 mr-2">
        @if (session('isPass') !== null || $receivedCertificate)
            <h1>{{ __(':title results', ['title' => $title]) }}</h1>
            @if (session('isPass') || $receivedCertificate)
                <h3>{{ __('Congratulations! You have passed the quiz with :score%.', ['score' => $score]) }}
                </h3>
                <p>{{ __('You have now completed this course. Your certificate of completion has been sent to your email.') }}
                </p>
                <div class="flex gap-3">
                    @livewire('email-results', ['quiz' => $course->quiz])
                    <form action="{{ localized_route('courses.show', $course) }}">
                        <button>
                            @svg('heroicon-o-arrow-left') {{ __('Back to :course', ['course' => $course->title]) }}
                        </button>
                    </form>
                </div>
            @elseif (!session('isPass'))
                <h3>{{ __('You scored :score%. Please try again.', ['score' => $score]) }}</h3>
            @endif
            <hr class="divider--thick" />
        @else
            <h1>{{ $title }}</h1>
        @endif
        @if (session('isPass'))
            <ol class="questions">
                @foreach ($questions as $key => $question)
                    <li>
                        <fieldset class="field question">
                            <legend class="counter__item">{{ $question->question }}</legend>
                            <x-interpretation class="interpretation--start"
                                name="{{ $question->getTranslation('question', 'en') }}"
                                namespace="course_quiz_question" />
                            @if (in_array($question->id, session('wrongAnswers')))
                                <x-banner type="error">
                                    {{ __('Wrong answer.') }}
                                </x-banner>
                            @else
                                <x-banner type="success">
                                    {{ __('Correct answer!') }}
                                </x-banner>
                            @endif
                            <x-interpretation class="interpretation--start"
                                name="{{ __('Choices for: :question', ['question' => $question->getTranslation('question', 'en')], 'en') }}"
                                namespace="course_quiz_question_choices" />
                            @foreach ($question->choices as $choice)
                                <div>
                                    @if (in_array($choice['value'], session('previousAnswers')[$question->id]))
                                        <p><strong>{{ $choice['label'] }}</strong></p>
                                    @else
                                        <p>{{ $choice['label'] }}</p>
                                    @endif
                                </div>
                                @if (in_array($choice['value'], $question->correct_choices))
                                    <div class="correct-answer flex items-center">
                                        <x-heroicon-o-check-circle />
                                        <p>{{ __('Correct answer') }}</p>
                                    </div>
                                @elseif (
                                    !in_array($choice['value'], $question->correct_choices) &&
                                        in_array($choice['value'], session('previousAnswers')[$question->id]))
                                    <div class="wrong-answer flex items-center">
                                        <x-heroicon-o-x-circle />
                                        <p>{{ __('Wrong answer') }}</p>
                                    </div>
                                @endif
                            @endforeach
                            <hr class="divider--thin" />
                        </fieldset>
                    </li>
                @endforeach
            </ol>
        @elseif (!$receivedCertificate)
            <!-- Form Validation Errors -->
            @include('partials.validation-errors')

            <form class="stack" action="{{ localized_route('quizzes.show-result', $course) }}" method="POST"
                novalidate>
                @csrf
                <ol class="questions">
                    @foreach ($questions as $key => $question)
                        <li>
                            <fieldset class="field @error('questions.{{ $question->id }}') field--error @enderror">
                                <legend class="counter__item">{{ $question->question }}</legend>
                                <x-interpretation class="interpretation--start"
                                    name="{{ $question->getTranslation('question', 'en') }}"
                                    namespace="course_quiz_question" />
                                @if (session('wrongAnswers'))
                                    @if (in_array($question->id, session('wrongAnswers')))
                                        <x-banner type="error">
                                            {{ __('Wrong answer.') }}
                                        </x-banner>
                                    @else
                                        <x-banner type="success">
                                            {{ __('Correct answer!') }}
                                        </x-banner>
                                    @endif
                                @endif
                                <x-interpretation class="interpretation--start"
                                    name="{{ __('Choices for: :question', ['question' => $question->getTranslation('question', 'en')], 'en') }}"
                                    namespace="course_quiz_question_choices" />
                                <x-hearth-checkboxes name="questions[{{ $question->id }}]" :options="$question->choices"
                                    :checked="old(
                                        'questions.' . $question->id,
                                        session('previousAnswers') &&
                                        array_key_exists($question->id, session('previousAnswers'))
                                            ? session('previousAnswers')[$question->id]
                                            : [],
                                    )" />
                                <x-hearth-error for="questions.{{ $question->id }}" />
                                <hr class="divider--thin" />
                            </fieldset>
                        </li>
                    @endforeach
                </ol>
                <button>{{ __('Submit') }}</button>
            </form>
        @endif
    </div>
</x-app-layout>
