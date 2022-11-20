<?php

return [

    // GENERAL CONFIGURATION

    "base_url" => env("DEVPILOT_BASE_URL", null),
    // The user whose account to authorize with i.e , you the developer
    "user_access_token" => env("DEVPILOT_USER_TOKEN", null),
    "user_access_token_passphrase" => env("DEVPILOT_USER_TOKEN_PASSPHRASE", null),

    // Details of the app to connect with
    "app_key" => env("DEVPILOT_APP_KEY", null),
    "app_secret" => env("DEVPILOT_APP_SECRET", null),





    // DEPLOYMENT SPIECIFIC CONFIGURATIONS

    "enable_deployment" => env("DEVPILOT_ENABLE_DEPLOYMENT", true),

    // Default branch to deploy if not specified
    "default_branch" => env("DEVPILOT_DEFAULT_BRANCH", null),

    // Log deployment messages or errors
    "enable_deployment_logging" => false,
    "deployment_log" => env("DEVPILOT_LOG_CHANNEL", env('LOG_CHANNEL', 'stack')),




    // ACTIVITY TRACKER SPECIFIC CONFIGURATIONS
    "enable_activity_tracking" => env("DEVPILOT_ENABLE_ACTIVITY_TRACKING", true),
    "activity_tracker_callback_url" => null,


    // LOG ACTIVITY TRACKING MESSAGES OR ERRORS
    "enable_activity_tracker_logging" => false,
    "activity_tracker_log" => env("DEVPILOT_LOG_CHANNEL", env('LOG_CHANNEL', 'stack')),


    // ERROR TRACKER SPIECIFIC CONFIGURATIONS
    "enable_error_tracking" => env("DEVPILOT_ENABLE_ERROR_TRACKING", true),
    "error_tracker_callback_url" => null,

    // LOG ACTIVITY TRACKING MESSAGES OR ERRORS
    "enable_error_tracker_logging" => false,
    "error_tracker_log" => env("DEVPILOT_LOG_CHANNEL", env('LOG_CHANNEL', 'stack')),


    // COMMAND FILTERS
    "disabled_commands" => env("DEVPILOT_DISABLED_COMMANDS", [
        "key:generate",
        "migrate:fresh",
        "migrate:refresh",
        "migrate:rollback",
    ]),
];
