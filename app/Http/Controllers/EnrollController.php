<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnrollController extends Controller
{
    public function store(Lesson $lesson)
    {
        $this->authorize('enroll', $lesson);

        abort_if(
            !auth()->user()->canEnroll($lesson),
            Response::HTTP_BAD_REQUEST,
            'You are already enrolled in this lesson.'
        );

        $lesson->students()->attach(auth()->user());

        return response(Response::HTTP_OK);
    }
}
