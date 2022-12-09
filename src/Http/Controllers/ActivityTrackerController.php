<?php

namespace TechiesAfrica\Devpilot\Http\Controllers;

use Illuminate\Http\Request;
use TechiesAfrica\Devpilot\Services\ActivityTracker\ActivityTrackerService;
use Throwable;

class ActivityTrackerController extends Controller
{
    public function test(Request $request)
    {
        $tracker = ActivityTrackerService::tracker();
        $tracker->verbose(true);
        $tracker->isTest(true);
        // $tracker->setShouldLog(true);
        $tracker->setResponseCallback(function ($response) {
            logger("Activity tracker response:", [$response]);
        });
    }
}
