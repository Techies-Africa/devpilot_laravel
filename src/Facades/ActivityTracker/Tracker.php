<?php

namespace TechiesAfrica\Devpilot\Facades\ActivityTracker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

class Tracker extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'Tracker';
    }
}
