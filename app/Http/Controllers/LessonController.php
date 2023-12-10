<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLesson;
use App\Http\Resources\Lesson as LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LessonController extends Controller
{
    public function store(StoreLesson $request)
    {
        $validated = $request->validated();

        Lesson::create([
            'teacher_id' => auth()->user()->id,
            ] + $validated);

        return response(Response::HTTP_CREATED);
    }

    public function show(Lesson $lesson)
    {
        return LessonResource::make($lesson->load('teacher'));
    }
}
