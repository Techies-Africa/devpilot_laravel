<?php

namespace App\Http\Middleware\Devpilot\ActivityTracker;


use Closure;
use Illuminate\Http\Request;
use TechiesAfrica\Devpilot\Facades\ActivityTracker;

class TrackerMiddleware
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
        // Ignore routes you don`t want to track
        ActivityTracker::setIgnoreRoutes([
            "web.read_file"
        ]);

        // Ignore middlewares you don`t want to track
        ActivityTracker::setIgnoreMiddlewares(["Barryvdh\Debugbar\Middleware\DebugbarEnabled"]);

        // Set middlewares used to identify authenticated users
        ActivityTracker::setAuthenticatedMiddlewares(["auth" , "admin" , "verified"]);

        // Set values for authenticated user mapping based of columns in the users table
        ActivityTracker::setUserFields(["id" => "id", "name" => "full_name", "email" => "email"]);

        // Toggle logging as necessary, default is true unless otherwise stated in your config
        // ActivityTracker::setShouldLog(false);

        ActivityTracker::preRequest($request);

        $response =  $next($request);

        // ActivityTracker::log("pushing data");

        ActivityTracker::postRequest($request)->push();

        return $response;
    }
}
