<?php

namespace App\Http\Controllers;

use App\Http\Resources\Lesson as LessonResource;
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

    public function destroy(Lesson $lesson)
    {
        $this->authorize('cancel', $lesson);

        abort_if(
            !auth()->user()->canCancel($lesson),
            Response::HTTP_BAD_REQUEST,
            'You are already not enrolled in this lesson.'
        );

        $lesson->students()->detach(auth()->user());

        return response(Response::HTTP_OK);
    }

    public function index()
    {
        $this->authorize('viewEnrolls', Lesson::class);

        $perPage = request('per_page', 15);

        $query = auth()->user()->lessons()->with('teacher')->getQuery();

        $query->when(request('name'), fn ($query, $name) => $query->where('name', 'LIKE', '%'.$name.'%'));

        $lessons = $query->simplePaginate($perPage)->withQueryString();

        return LessonResource::collection($lessons);
    }
}
