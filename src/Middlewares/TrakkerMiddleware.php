<?php

namespace TechiesAfrica\LaravelTrakker\Middlewares;

use TechiesAfrica\LaravelTrakker\Services\TrakkerService;
use Closure;
use Illuminate\Http\Request;

class TrakkerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tracker = TrakkerService::tracker();
        $tracker->setIgnoreRoutes([
            "web.read_file"
        ]);
        $tracker->setAuthenticatedMiddlewares(["auth" , "admin" , "verified"]);
        $tracker->setIgnoreMiddlewares(["Barryvdh\Debugbar\Middleware\DebugbarEnabled"]);
        $tracker->setUserFields(["id" => "id", "name" => "full_name", "email" => "email"]);
        $tracker->preRequest($request);
        $response =  $next($request);

        TrakkerService::log("pushing data");
        $tracker->postRequest($request)
            ->push();
        // }
        return $response;
    }
}
