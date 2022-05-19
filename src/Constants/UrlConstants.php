<?php

namespace TechiesAfrica\Devpilot\Constants;


class UrlConstants
{
    const VERSION = "v1";

    const LOG_ACTIVITY = "/activity-tracker/visits/log";

    public static function get($relative_endpoint , $version = self::VERSION)
    {
        return config("devpilot.base_url")."api/".$version.$relative_endpoint;
    }
}
