<?php

namespace App\Http\Controllers;

use App\Enum\UserType;
use App\Http\Requests\StoreUser;
use App\Http\Resources\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function store(StoreUser $request)
    {
        $validated = $request->validated();

        User::create($validated);

        return response(Response::HTTP_CREATED);
    }

    public function index()
    {
        $perPage = request('per_page', 15);

        $query = User::query();

        if (auth()->user()->isStudent()) {
            $types = [UserType::TEACHER];
        }

        if (auth()->user()->isAdmin()) {
            $types = request('types', []);
        }

        $query->when(count($types) > 0, fn($query, $types) => $query->whereIn('type', $types));
        $query->when(request('name'), fn ($query, $name) => $query->where('name', 'LIKE', '%'.$name.'%'));
        $query->when(request('email'), fn ($query, $email) => $query->where('email', $email));

        $users = $query->simplePaginate($perPage)->withQueryString();

        return UserResource::collection($users);
    }
}
