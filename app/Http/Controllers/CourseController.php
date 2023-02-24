<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function show(Course $course): View
    {
        $user = Auth::user();
        $courseUser = $user->courses->find($course->id);
        $finishedCourse = $course->isFinished($user);
        $receivedCertificate = $courseUser?->getRelationValue('pivot')->received_certificate_at;
        $hasQuiz = $course->quiz()->count() && $course->quiz?->questions()->count();

        return view('courses.show', [
            'user' => $user,
            'course' => $course,
            'modules' => $course->modules,
            'finishedCourse' => $finishedCourse,
            'receivedCertificate' => $receivedCertificate,
            'hasQuiz' => $hasQuiz,
        ]);
    }
}
