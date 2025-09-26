<?php

namespace App\Http\Controllers;

use App\Models\User;

final class UsersDestroyController extends Controller
{
    public function __invoke(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
