<?php

namespace TechiesAfrica\Devpilot\Tests\Feature\ActivityTracker;

use TechiesAfrica\Devpilot\Tests\TestCase;
use TechiesAfrica\Devpilot\Traits\General\ConfigurationTrait;

class CommandTest extends TestCase
{
    use ConfigurationTrait;
    function test_status_command()
    {
        $this->artisan("devpilot:tracker:status")
            ->expectsTable([
                "#",
                "FIELD",
                "VALUE",
            ], [
                [1, "User Access Token Check", !empty($this->getAuthenticationUserAccessToken()) ? "Passed" : "Failed. Kindly set it in your env."],
                [2, "App Key Check", !empty($this->getAuthenticationAppKey()) ? "Passed" : "Failed. Kindly set it in your env."],
                [3, "App Secret Check", !empty($this->getAuthenticationAppSecret()) ? "Passed" : "Failed. Kindly set it in your env."],
                [4, "Logging Enabled Check", $this->isActivityTrackerEnabled() ? "true" : "false"],
            ])
            ->expectsOutput("Activity Tracker status check completed....");
    }
}
