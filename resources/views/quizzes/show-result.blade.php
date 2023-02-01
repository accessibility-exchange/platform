<x-app-layout page-width="wide">
    <x-slot name="header">
    </x-slot>
    <div class="stack ml-2 mr-2">

    </div>
    <div class="stack ml-2 mr-2">
        <h1>{{ __('Quiz results') }}</h1>
        <h3>{{ __('Congratulations! You have passed the quiz.') }}</h3>
        @livewire('email-results', ['quiz' => $quiz])
    </div>
</x-app-layout>
