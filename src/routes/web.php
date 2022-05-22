<?php

use Illuminate\Support\Facades\Route;
use TechiesAfrica\Devpilot\Http\Controllers\ActivityTrackerController;

Route::as("devpilot.")->prefix("devpilot")->group(function () {
    Route::get("/activity-tracker/test", [ActivityTrackerController::class, 'test'])
        ->middleware("activity_tracker")
        ->name('activity_tracker.test');
});
