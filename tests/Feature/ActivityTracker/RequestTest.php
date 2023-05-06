<?php

namespace TechiesAfrica\Devpilot\Tests\Feature\ActivityTracker;

use TechiesAfrica\Devpilot\Tests\TestCase;

class RequestTest extends TestCase
{
    function test_test_command()
    {
        $this->artisan("devpilot:tracker:test")
            // ->expectsOutput("Request sent successully");
            ->assertSuccessful("Request sent successully");
    }
}
