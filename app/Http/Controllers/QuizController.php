<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizResultRequest;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

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
        $quizResults = $quizScore / $numberOfQuestions >= $quiz->minimum_score;
        $quizUser = $user->quizzes->where('id', $quiz->id)->first()->pivot ?? null;
        if ($quizScore / $numberOfQuestions >= $quiz->minimum_score) {
            if ($quizUser) {
                $attempts = $quizUser->attempts;
                $user->quizzes()->updateExistingPivot(
                    $quiz->id, [
                        'attempts' => $attempts + 1,
                        'score' => $quizScore / $numberOfQuestions,
                    ]
                );
            } else {
                $user->quizzes()->attach(
                    $quiz->id, [
                        'attempts' => 1,
                        'score' => $quizScore / $numberOfQuestions,
                    ]
                );
            }
            $user->courses()->updateExistingPivot(
                $quiz->course->id, [
                    'received_certificate_at' => now(),
                ]
            );
        } else {
            if ($quizUser) {
                $attempts = $quizUser->attempts;
                $user->quizzes()->updateExistingPivot(
                    $quiz->id, [
                        'attempts' => $attempts + 1,
                        'score' => $quizScore / $numberOfQuestions,
                    ]
                );
            } else {
                $user->quizzes()->attach(
                    $quiz->id, [
                        'attempts' => 1,
                        'score' => $quizScore / $numberOfQuestions,
                    ]
                );
            }
        }

        return view('quizzes.show-result', ['quiz' => $quiz, 'results' => $quizResults]);
    }
}
