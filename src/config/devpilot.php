<?php

return [
    
    // AUTHENTICATION CONFIGURATION
    "authentication" => [
        "base_url" => env("DEVPILOT_BASE_URL", null),
        // The user whose account to authorize with i.e , you the developer
        "user_access_token" => env("DEVPILOT_USER_TOKEN", null),
        "user_access_token_passphrase" => env("DEVPILOT_USER_TOKEN_PASSPHRASE", null),

        // Details of the app to connect with
        "app_key" => env("DEVPILOT_APP_KEY", null),
        "app_secret" => env("DEVPILOT_APP_SECRET", null),

    ],

    // DEPLOYMENT SPIECIFIC CONFIGURATIONS
    "deployment" => [
        "emable" => env("DEVPILOT_ENABLE_DEPLOYMENT", true),
        "default_branch" => env("DEVPILOT_DEFAULT_BRANCH", null),
        "emable_logging" => false,
        "log_channel" => env("DEVPILOT_LOG_CHANNEL", env('LOG_CHANNEL', 'stack')),
        "callback_url" => null
    ],


    // ACTIVITY TRACKER SPECIFIC CONFIGURATIONS
    "activity_tracker" => [
        "emable" => env("DEVPILOT_ENABLE_ACTIVITY_TRACKER", true),
        "emable_logging" => false,
        "log_channel" => env("DEVPILOT_LOG_CHANNEL", env('LOG_CHANNEL', 'stack')),
        "callback_url" => null
    ],

    // ERROR TRACKER SPIECIFIC CONFIGURATIONS
    "error_tracker" => [
        "emable" => env("DEVPILOT_ENABLE_ERROR_TRACKER", true),
        "trim_stacktrace_path" => false,
        "emable_logging" => false,
        "log_channel" => env("DEVPILOT_LOG_CHANNEL", env('LOG_CHANNEL', 'stack')),
        "callback_url" => null
    ],

    // COMMAND FILTERS
    "disabled_commands" => env("DEVPILOT_DISABLED_COMMANDS", [
        "key:generate",
        "migrate:fresh",
        "migrate:refresh",
        "migrate:rollback",
    ]),
];
