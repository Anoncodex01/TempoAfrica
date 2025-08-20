<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        // return $request->expectsJson() ? null : '/';

        return $request->expectsJson()
        ? response()->json([
            'success' => false,
            'message' => 'Authentication required. Please provide a valid token.',
        ], 401)
        : '/';
    }
}
