<?php

// app/Http/Middleware/HtmlMinifyMiddleware.php

namespace App\Http\Middleware;

use voku\helper\HtmlMin;

class HtmlMinifyMiddleware
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        if (
            $response instanceof \Illuminate\Http\Response
            && str_contains($response->headers->get('Content-Type'), 'text/html')
        ) {
            $minifier = new HtmlMin();
            $response->setContent(
                $minifier->minify($response->getContent())
            );
        }

        return $response;
    }
}
