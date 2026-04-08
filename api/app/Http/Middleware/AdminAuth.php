<?php

namespace App\Http\Middleware;

use App\Models\Operator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $operator = Operator::query()->where('token', $token)->first();

        if (! $operator) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->attributes->set('_operator', $operator);

        return $next($request);
    }
}
