<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

final class HealthController extends Controller
{
    public function __invoke()
    {
        return response()->json(['status' => 'ok'], Response::HTTP_OK);
    }
}
