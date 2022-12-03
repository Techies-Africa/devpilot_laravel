<?php

namespace TechiesAfrica\Devpilot\Tests\Feature\ActivityTracker;

use TechiesAfrica\Devpilot\Tests\TestCase;

class CommandTest extends TestCase
{
    function test_status_command()
    {
        $this->artisan("devpilot:tracker:status")
            ->expectsTable([
                "#",
                "FIELD",
                "VALUE",
            ], [
                [1, "User Access Token Check", !empty(config("devpilot.user_access_token")) ? "Passed" : "Failed. Kindly set it in your env."],
                [2, "App Key Check", !empty(config("devpilot.app_key")) ? "Passed" : "Failed. Kindly set it in your env."],
                [3, "App Secret Check", !empty(config("devpilot.app_secret")) ? "Passed" : "Failed. Kindly set it in your env."],
                [4, "Logging Enabled Check", config("devpilot.enable_activity_tracking") ? "true" : "false"],
            ])
            ->expectsOutput("Activity Tracker status check completed....");
    }
}
