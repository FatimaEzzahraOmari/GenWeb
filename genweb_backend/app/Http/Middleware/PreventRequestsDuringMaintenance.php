
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventRequestsDuringMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->app->isDownForMaintenance()) {
            throw new MaintenanceModeException(now(), null, 'Maintenance mode is active.');
        }

        return $next($request);
    }
}