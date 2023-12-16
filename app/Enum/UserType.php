<?php

namespace App\Enum;

enum UserType:int
{
    case TEACHER = 1;
    case STUDENT = 2;
    case ADMIN = 3;

    public function label(): string
    {
        return match ($this) {
            self::TEACHER => 'Teacher',
            self::STUDENT => 'Student',
            self::ADMIN => 'Admin',
        };
    }
}