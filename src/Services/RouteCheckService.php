<?php

namespace TechiesAfrica\LaravelTrakker\Services;

use Illuminate\Routing\Route;

class RouteCheckService{

    public static function checkIfRouteIsIgnored(Route $route , array $ignored_routes): bool
    {

        $route_url = $route->uri();
        $route_name = $route->getName();

        foreach ($ignored_routes as $value) {
            if(in_array($value , [$route_name , $route_url])){
                return true;
            }
        }
        return false;
    }


    public static function checkIfMiddlewareIsIgnored(Route $route , array $ignored_middlwares): bool
    {

        $middlewares = $route->action["middleware"] ?? [];

        foreach ($ignored_middlwares as $value) {
            if(in_array($value , $middlewares)){
                return true;
            }
        }
        return false;
    }


    public static function getPageType(Route $route , array $authenticated_middlewares): string
    {

        $middlewares = $route->action["middleware"] ?? [];

        foreach ($authenticated_middlewares as $value) {
            if(in_array($value , $middlewares)){
                return "Authenticated";
            }
        }

        return "Unauthenticated";
    }
}
