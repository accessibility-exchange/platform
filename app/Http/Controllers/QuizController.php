<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Models\Quiz;

class QuizController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    // public function show(Quiz $quiz)
    // {
    //     $questions = $quiz->questions;
    //     return view('quizzes.show', [
    //         'quiz' => $quiz,
    //         'title' => $quiz->title,
    //         'questions' => $questions,
    //     ]);
    // }

    // public function store(StoreQuizRequest $request)
    // {
    //     $data = $request->validated();

    //     dd($request->post());

    //     foreach($request->post() as $response) {

    //     }

    //     $quiz->users()->attach($data['quiz_id']);

    //     return redirect(\localized_route('quizzes.show-result', ['certificate' => '']));
    // }
}
