<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5173');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-XSRF-TOKEN, Accept');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        // Gérer les requêtes OPTIONS (preflight)
        if ($request->isMethod('OPTIONS')) {
            return response()->json([], 200)->withHeaders([
                'Access-Control-Allow-Origin' => 'http://localhost:5173',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-XSRF-TOKEN, Accept',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
        }

        return $response;
    }
}