<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $user?->update(['last_seen' => new DateTime(),]);

        Log::debug("User ID: " . $user->id);

        return $next($request);
    }
}
