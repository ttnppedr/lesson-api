<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLesson;
use App\Http\Requests\UpdateLesson;
use App\Http\Resources\Lesson as LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Lesson::class, 'lesson');
    }

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

    public function index()
    {
        $perPage = request('per_page', 15);

        $query = Lesson::query();

        $query->when(request('teachers'), fn ($query, $teachers) => $query->whereIn('teacher_id', $teachers));
        $query->when(request('name'), fn ($query, $name) => $query->where('name', 'LIKE', '%'.$name.'%'));

        $query->with('teacher');

        $lessons = $query->simplePaginate($perPage)->withQueryString();

        return LessonResource::collection($lessons);
    }

    public function update(UpdateLesson $request, Lesson $lesson)
    {
        $validated = $request->validated();

        $lesson->update($validated);

        return LessonResource::make($lesson->refresh()->load('teacher'));
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response(Response::HTTP_OK);
    }
}
