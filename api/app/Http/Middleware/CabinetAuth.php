<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CabinetAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('cabinet_user_id')) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                ],
            ], 401);
        }

        return $next($request);
    }
}
