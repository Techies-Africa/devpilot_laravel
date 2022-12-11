<?php

return [

    // GENERAL CONFIGURATION
    "general" => [
        "base_url" => env("DEVPILOT_BASE_URL", null),
        "hostname" => null
    ],

    // AUTHENTICATION CONFIGURATION
    "authentication" => [
        // The user whose account to authorize with i.e , you the developer
        "user_access_token" => env("DEVPILOT_USER_TOKEN", null),
        "user_access_token_passphrase" => env("DEVPILOT_USER_TOKEN_PASSPHRASE", null),

        // Details of the app to connect with
        "app_key" => env("DEVPILOT_APP_KEY", null),
        "app_secret" => env("DEVPILOT_APP_SECRET", null),

    ],


    // DEPLOYMENT SPIECIFIC CONFIGURATIONS
    "deployment" => [
        "enable" => env("DEVPILOT_ENABLE_DEPLOYMENT", true),
        "default_branch" => env("DEVPILOT_DEFAULT_BRANCH", null),
        "enable_logging" => false,
        "log_channel" => env("DEVPILOT_LOG_CHANNEL", env("LOG_CHANNEL", "stack")),
        "callback_url" => null
    ],


    // ACTIVITY TRACKER SPECIFIC CONFIGURATIONS
    "activity_tracker" => [
        "enable" => env("DEVPILOT_ENABLE_ACTIVITY_TRACKER", true),
        "enable_logging" => false,
        "log_channel" => env("DEVPILOT_LOG_CHANNEL", env("LOG_CHANNEL", "stack")),
        "callback_url" => null,

        // Ignore routes you don`t want to track
        "ignore_routes" => [
            // "web.read_file"
        ],

        // Ignore middlewares you don`t want to track
        "ignore_middlewares" => [
            //"Barryvdh\Debugbar\Middleware\DebugbarEnabled"
        ],

        "authenticated_middlewares" => [
            "auth",
            "admin",
            "verified"
        ],

        "user_fields" => [
            "id" => "id",
            "name" => "name",
            "email" => "email"
        ]

    ],

    // ERROR TRACKER SPIECIFIC CONFIGURATIONS
    "error_tracker" => [
        "enable" => env("DEVPILOT_ENABLE_ERROR_TRACKER", true),
        "project_path" => base_path(),
        "strip_path" => base_path(),
        "enable_logging" => false,
        "log_channel" => env("DEVPILOT_LOG_CHANNEL", env("LOG_CHANNEL", "stack")),
        "callback_url" => null,
        "ignored_classes" => [],
        "send_code" => false,
        "metadata_filters" => [
            "access_token", // case-insensitive: "access_token", "ACCESS_TOKEN", "AcCeSs_ToKeN"
            "/^cc_/",        // prefix match: "cc_number" "cc_cvv" "cc_expiry"
            "password",
            "cookie",
            "token",
            "secret",
            "authorization",
            "php-auth-user",
            "php-auth-pw",
            "php-auth-digest"
        ]
    ],

    // COMMAND FILTERS
    "command_filter" => [
        "disabled" => env("DEVPILOT_DISABLED_COMMANDS", [
            "key:generate",
            "migrate:fresh",
            "migrate:refresh",
            "migrate:rollback",
        ])
    ],
];
