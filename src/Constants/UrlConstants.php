<?php

namespace TechiesAfrica\Devpilot\Constants;


class UrlConstants
{
    public static function get($relative_endpoint): string
    {
        return config("devpilot.base_url") . $relative_endpoint;
    }

    static function logActivity(): string
    {
        return self::get("/app/activity-tracker/visits/log");
    }

    // Deployment
    static function deploy(): string
    {
        return self::get("/app/deployments/deploy");
    }

    static function deploymentInformation(): string
    {
        return self::get("/app/deployments/information");
    }

}
