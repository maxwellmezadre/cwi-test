<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

final class UsersDestroyController extends Controller
{
    public function __invoke(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
