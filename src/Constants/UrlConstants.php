<?php

namespace TechiesAfrica\Devpilot\Constants;

use TechiesAfrica\Devpilot\Traits\General\ConfigurationTrait;

class UrlConstants
{
    use ConfigurationTrait;
    public static function get($relative_endpoint): string
    {
        return (new UrlConstants)->getGeneralBaseUrl() . $relative_endpoint;
    }

    // Activity tracking
    static function logActivity(): string
    {
        return self::get("/workspace/app/activity-tracker/visits/log");
    }

    // Error tracking
    static function logError(): string
    {
        return self::get("/workspace/app/error-tracker/log");
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

    static function loadEnv(): string
    {
        return self::get("/workspace/app/env/load");
    }

    static function saveEnv(): string
    {
        return self::get("/workspace/app/env/save");
    }

    static function executeServerCommands(): string
    {
        return self::get("/server/execute");
    }
}
