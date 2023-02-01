<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizResultRequest;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function show(Course $course): View
    {
        $quiz = $course->quiz;

        return view('quizzes.show', [
            'course' => $course,
            'title' => $quiz->title,
            'questions' => $quiz->questions,
        ]);
    }

    public function storeQuizResult(StoreQuizResultRequest $request, Course $course): View | RedirectResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $correctQuestions = 0;
        $quiz = $course->quiz->loadCount('questions');
        $isPass = false;
        $validator = Validator::make([], []);
        $previousAnswers = [];
        $wrongAnswers = [];

        foreach ($quiz->questions as $question) {
            $previousAnswers[$question->id] = $data['questions'][$question->id];
            if ($question->correct_choices == $data['questions'][$question->id]) {
                $correctQuestions++;
            } else {
                $validator->errors()->add('questions.'.$question->id, __('Wrong answer'));
                $wrongAnswers[] = $question->id;
            }
        }

        $quizScore = $correctQuestions / $quiz->questions_count;
        $quizUser = $quiz->users->find($user->id)?->getRelationValue('pivot');
        if ($quizUser) {
            $attempts = $quizUser->attempts;
            $user->quizzes()->updateExistingPivot(
                $quiz->id, [
                    'attempts' => $attempts + 1,
                    'score' => $quizScore,
                ]
            );
        } else {
            $user->quizzes()->attach(
                $quiz->id, [
                    'attempts' => 1,
                    'score' => $quizScore,
                ]
            );
        }
        if ($quizScore >= $quiz->minimum_score) {
            $isPass = true;
            $user->courses()->updateExistingPivot(
                $quiz->course->id, [
                    'received_certificate_at' => now(),
                ]
            );
        } else {
            return redirect(localized_route('quizzes.show', $course))->with(['previousAnswers' => $previousAnswers, 'wrongAnswers' => $wrongAnswers])->withErrors($validator);
        }

        return view('quizzes.show-result', ['quiz' => $quiz, 'results' => $isPass]);
    }
}
