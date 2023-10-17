<?php

namespace App\Livewire;

use App\Mail\QuizResults;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EmailResults extends Component
{
    public Quiz $quiz;

    public User $user;

    public function mount($quiz)
    {
        $this->quiz = $quiz;

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
