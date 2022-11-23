<x-app-wide-layout>
    <x-slot name="header">
    </x-slot>
    <div class="stack ml-2 mr-2">

    </div>
    @if ($result)
        <div class="stack ml-2 mr-2">
            <h2>{{ __('You have passed the quiz.') }}</h2>
            <p>{{ __('You can send the quiz result to your manager:') }}</p>
            <form class="stack" action="{{ localized_route('quizzes.email', $quiz) }}" method="POST" novalidate>
                @csrf
                <div class="field @error('email') field--error @enderror">
                    <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                    <x-hearth-input name="email" type="email" :value="old('email')" required autofocus />
                    <x-hearth-error for="email" />
                </div>
                <button>Send</button>
            </form>
        </div>
    @else
        <div class="stack ml-2 mr-2">
            <h2>{{ __('You have not passed the quiz.') }}</h2>
        </div>
    @endif
</x-app-wide-layout>
