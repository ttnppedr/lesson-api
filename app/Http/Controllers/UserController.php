<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
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
}
