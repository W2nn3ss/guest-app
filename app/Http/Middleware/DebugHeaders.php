<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DebugHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        $memoryUsage = memory_get_peak_usage(true) / 1024;

        $response->headers->set('X-Debug-Time', $executionTime);
        $response->headers->set('X-Debug-Memory', $memoryUsage);

        return $response;
    }
}
