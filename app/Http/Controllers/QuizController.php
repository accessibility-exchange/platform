<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizResultRequest;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class QuizController extends Controller
{
    public function show(Course $course): View
    {
        $quiz = $course->quiz;

        return view('quizzes.show', [
            'course' => $course,
            'quiz' => $quiz,
            'title' => $quiz->title,
            'questions' => $quiz->questions,
        ]);
    }

    public function storeQuizResult(StoreQuizResultRequest $request, Course $course): View
    {
        $data = $request->validated();
        $user = Auth::user();
        $correctQuestions = 0;
        $quiz = $course->quiz;
        $quizWithCounts = $quiz->loadCount('questions');
        $isPass = false;
        $validator = Validator::make([], []);

        foreach ($quizWithCounts->questions as $question) {
            if ($question->getCorrectChoices() == $data['questions'][$question->id]) {
                $correctQuestions++;
            } else {
                $validator->errors()->add('questions['.$question->id.']', __('Wrong answer'));
            }
        }

        //@phpstan-ignore-next-line
        if ($quizWithCounts->questions_count > 0) {
            $quizScore = $correctQuestions / $quizWithCounts->questions_count;
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
                throw new ValidationException($validator);
            }
        }

        return view('quizzes.store-result', ['quiz' => $quiz, 'results' => $isPass]);
    }
}
