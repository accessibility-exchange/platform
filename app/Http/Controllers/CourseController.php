<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(): View
    {
        return view('courses.index', [
            'courses' => Course::all(),
        ]);
    }

    public function show(Course $course): View
    {
        $user = Auth::user();
        $courseUser = $user->courses->find($course->id);
        $finishedCourse = $courseUser?->getRelationValue('pivot')->finished_at;
        $receivedCertificate = $courseUser?->getRelationValue('pivot')->received_certificate_at;

        return view('courses.show', [
            'user' => $user,
            'course' => $course,
            'modules' => $course->modules,
            'finishedCourse' => $finishedCourse,
            'receivedCertificate' => $receivedCertificate,
        ]);
    }
}
