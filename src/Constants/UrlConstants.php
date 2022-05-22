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
        return self::get("/workspace/app/activity-tracker/visits/log");
    }

    // Deployment
    static function deploy(): string
    {
        return self::get("/workspace/app/deployments/deploy");
    }

    static function deploymentInformation(): string
    {
        return self::get("/workspace/app/deployments/information");
    }

    static function executeAppCommands(): string
    {
        return self::get("/workspace/app/server/execute");
    }

    static function executeServerCommands(): string
    {
        return self::get("/server/execute");
    }

}
