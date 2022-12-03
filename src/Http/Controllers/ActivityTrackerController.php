<?php

namespace TechiesAfrica\Devpilot\Http\Controllers;

use Illuminate\Http\Request;
use TechiesAfrica\Devpilot\Services\ActivityTracker\ActivityTrackerService;

class ActivityTrackerController extends Controller
{
    public function test(Request $request)
    {
        $tracker = ActivityTrackerService::tracker();
        $tracker->isTest(true);
    }
}
