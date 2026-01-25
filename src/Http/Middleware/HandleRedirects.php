<?php

namespace MonstreX\VoyagerSite\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use MonstreX\VoyagerSite\Models\Redirect;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Schema::hasTable('site_redirects')) {
            return $next($request);
        }

        $path = '/'.ltrim($request->path(), '/');

        $redirect = Redirect::findByPath($path);
        if ($redirect) {
            $redirect->recordHit();

            $destination = $redirect->to_url;
            if (!str_starts_with($destination, 'http')) {
                $destination = url($destination);
            }

            return redirect($destination, $redirect->status_code);
        }

        return $next($request);
    }
}
