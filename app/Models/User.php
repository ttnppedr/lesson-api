<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'type' => UserType::class,
    ];

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function isTeacher(): bool
    {
        return $this->type === UserType::TEACHER;
    }

    public function isAdmin(): bool
    {
        return $this->type === UserType::ADMIN;
    }

    public function isStudent(): bool
    {
        return $this->type === UserType::STUDENT;
    }

    public function teachLessons()
    {
        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id');
    }

    public function canEnroll(Lesson $lesson): bool
    {
        return !$this->lessons->contains($lesson);
    }

    public function canCancel(Lesson $lesson): bool
    {
        return $this->lessons->contains($lesson);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
