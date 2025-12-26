<?php

// app/Http/Middleware/AssetCacheMiddleware.php

namespace App\Http\Middleware;

class AssetCacheMiddleware
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        if ($request->is('assets/js/*') || $request->is('assets/css/*')) {
            $response->headers->set(
                'Cache-Control',
                'public, max-age=31536000, immutable'
            );
        }

        return $response;
    }
}
