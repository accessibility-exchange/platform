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
                <fieldset class="field @error('questions[{{ $question->id }}]') field--error @enderror">
                    <legend>{{ $question->question . '?' }}</legend>
                    <x-hearth-checkboxes name="questions[{{ $question->id }}]" :options="$question->getChoices()" required />
                    <x-hearth-error for="questions[{{ $question->id }}]" />
                </fieldset>
            @endforeach
            <button>submit</button>
        </form>
    </div>
</x-app-layout>
