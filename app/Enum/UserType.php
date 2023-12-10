<?php

namespace App\Enum;

enum UserType:int
{
    case TEACHER = 1;
    case STUDENT = 2;

    public function label(): string
    {
        return match ($this) {
            self::TEACHER => 'Teacher',
            self::STUDENT => 'Student',
        };
    }
}