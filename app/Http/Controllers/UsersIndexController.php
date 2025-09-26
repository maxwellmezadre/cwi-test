<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

final class UsersIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        return UserResource::collection(User::query()->latest()->get());
    }
}
