<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BeforeMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Credentials', 'true');
        header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Cache-Control, Content-Type, Accept, Access-Control-Request-Method, Authorization, Accept-Language, Locale");
        return $next($request);
    }
}
