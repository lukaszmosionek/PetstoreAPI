<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (
            // $request->header('Content-Type') !== 'application/json' ||
            $request->header('Accept') !== 'application/json'
        ) {
            return response()->json([
                'success' => false,
                // 'message' => 'Invalid headers. JSON required. Set Content-Type to application/json and Accept to application/json',
                'message' => 'Invalid headers. JSON required. Set Accept to application/json in Headers'
            ], 406);
        }

        return $next($request);
    }
}
