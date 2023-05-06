<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

use Illuminate\Support\Facades\Http;
use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Services\ActivityTracker\ActivityTrackerService;
use TechiesAfrica\Devpilot\Services\General\Guzzle\GuzzleService;
use TechiesAfrica\Devpilot\Traits\Commands\Errors\ErrorHandlerTrait;

trait ActivityTrackerTrait
{
    use ErrorHandlerTrait;
    public function test()
    {
        $guzzle = new GuzzleService(
            [
                'User-Agent' => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36",
            ]
        );
        $process = $guzzle->get(route("devpilot.activity_tracker.test"));
        if ($process["status"] == 200) {
            $this->line('<fg=green;>Request sent successully. <fg=white;bg=black></>');
        } else {
            $this->line('<fg=red;>' . $process["message"] . ' <fg=white;bg=black></>');
        }
        $process;
    }
}
