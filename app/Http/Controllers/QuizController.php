<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendQuizResultRequest;
use App\Http\Requests\StoreQuizResultRequest;
use App\Mail\QuizResult;
use App\Models\Quiz;

class QuizController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        $questions = $quiz->questions;

        return view('quizzes.show', [
            'quiz' => $quiz,
            'title' => $quiz->title,
            'questions' => $questions,
        ]);
    }

    //separate controller for quiz/user or separate method like storeResult

    public function storeQuizResult(StoreQuizResultRequest $request, Quiz $quiz)
    {
        $data = $request->validated();

        $numberOfQuestions = 0;
        $quizScore = 0;

        foreach ($quiz->questions as $question) {
            $numberOfQuestions++;
            $userChoices = $data['question_'.$question->id];
            if (count($userChoices) < $question->minimum_choices) {
                continue;
            } else {
                $numberOfCorrectAnswers = 0;
                foreach ($question->choices as $choice) {
                    if (in_array($choice->value, $userChoices) && $choice->is_answer) {
                        $numberOfCorrectAnswers++;
                    }
                }
                if ($numberOfCorrectAnswers >= $question->minimum_choices) {
                    $quizScore++;
                }
            }
        }
        $quizResult = $quizScore / $numberOfQuestions >= $quiz->minimum_score;
        if ($quizScore / $numberOfQuestions >= $quiz->minimum_score) {
            // Update quiz_user table with the score and attempts
        } else {
            // Update quiz_user table with the score and attempts
        }

        return view('quizzes.show-result', ['quiz' => $quiz, 'result' => $quizResult]);
    }

    public function store(StoreQuizRequest $request, Quiz $quiz)
    {
    }

    public function email(SendQuizResultRequest $request, Quiz $quiz)
    {
        $data = $request->validated();

        Mail::to($data['manager_email'])->send(new QuizResult('user name'));

        flash(__('You have successfully sent the quiz result email.'), 'success');
    }
}
