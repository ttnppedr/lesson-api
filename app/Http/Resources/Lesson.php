<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Lesson extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'teacher' => User::make($this->teacher),
            'name' => $this->name,
            'lesson_time' => Carbon::make($this->lesson_time)->format('H:i'),
            'description' => $this->description,
        ];
    }
}
