<x-app-layout page-width="wide">
    <x-slot name="header">
    </x-slot>
    <div class="stack ml-2 mr-2">
        <x-slot name="title">{{ $title }}</x-slot>
    </div>
    <div class="stack ml-2 mr-2">
        <form class="stack" action="{{ localized_route('quizzes.store-result', $course) }}" method="POST" novalidate>
            @csrf
            @foreach ($questions as $question)
                <fieldset class="field @error('questions.{{ $question->id }}') field--error @enderror">
                    @if (session('wrongAnswers'))
                        @if (in_array($question->id, session('wrongAnswers')))
                            <x-banner type="error">
                                {{ __('Please try again.') }}
                            </x-banner>
                        @else
                            <x-banner type="success">
                                {{ __('Correct answer!') }}
                            </x-banner>
                        @endif
                    @endif
                    <legend>{{ $question->question . '?' }}</legend>
                    <x-hearth-checkboxes name="questions[{{ $question->id }}]" :options="$question->getChoices()" :checked="session('previousAnswers') && array_key_exists($question->id, session('previousAnswers'))
                        ? session('previousAnswers')[$question->id]
                        : []" />
                    <x-hearth-error for="questions.{{ $question->id }}" />
                </fieldset>
            @endforeach
            <button>{{ __('Submit') }}</button>
        </form>
    </div>
</x-app-layout>
