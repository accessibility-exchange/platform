 <x-app-wide-layout>
     <x-slot name="header">
     </x-slot>
     <div class="stack ml-2 mr-2">

     </div>
     @if ($results)
         <div class="stack ml-2 mr-2">
             <h1>{{ __('Quiz results') }}</h1>
             <h3>{{ __('Congratulations! You have passed the quiz.') }}</h3>
             @livewire('email-results', ['quiz' => $quiz])
         </div>
     @else
         <div class="stack ml-2 mr-2">
             <h2>{{ __('You have not passed the quiz.') }}</h2>
         </div>
     @endif
 </x-app-wide-layout>
