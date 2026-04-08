<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AdminRoleAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $operator = $request->attributes->get('_operator');

        if (! $operator || ! $operator->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
