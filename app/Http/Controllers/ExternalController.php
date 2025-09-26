<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

final class ExternalController extends Controller
{
    public function __invoke()
    {
        $response = Http::baseUrl(config('services.external.url'))
            ->get('/ping');

        return $response->ok()
            ? response()->json($response->json(), $response->status())
            : response()->json(['error' => 'external_service_unavailable'], Response::HTTP_BAD_GATEWAY);
    }
}
