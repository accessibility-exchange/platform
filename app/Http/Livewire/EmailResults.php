<?php

namespace App\Http\Livewire;

use App\Mail\QuizResults;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EmailResults extends Component
{
    public function mount($quiz, $results)
    {
        $this->quiz = $quiz;
        $this->results = $results;

        $this->user = Auth::user();
    }

    protected $listeners = ['send'];

    public function render()
    {
        return view('livewire.email-results');
    }

    public function send()
    {
        Mail::to($this->user->email)->send(new QuizResults($this->quiz, $this->user->name));
    }
}
