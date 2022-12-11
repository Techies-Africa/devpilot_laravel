<?php

namespace TechiesAfrica\Devpilot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static $this setIgnoreRoutes(array $data) Set route names you want to ignore
 * @method static $this setIgnoreMiddlewares(array $data) Set middlewares you want to ignore
 * @method static $this setAuthenticatedMiddlewares(array $data) Set middlewares to help detect authenticated users
 * @method static $this setShouldLog(bool $value) Set if activity tracker should send data to Devpilot
 * @method static $this setEnableLogging(bool $value) Set if you want the response from Devpilot to be logged
 * @method static bool isAjax() Check if the request is ajax request
 * @method static bool canPush() Check if all is good and data can sent to Devpilot
 * @method static $this setResponseCallback(Closure $callback) Set a callback function that would be triggered after the request has been sent to Devpilot
 * @method static $this setUserFields(array $data) Set user fields map
 * @method static $this setMetadata(array $data) Add extra data to the activity record
 * @method static $this verbose(bool $value = false) Throw any internal errors.
 * @method static $this preRequest(\Illuminate\Http\Request $request) Initialize incoming request.
 * @method static $this postRequest(\Illuminate\Http\Request $request) Re-initialize request just before returning response
 * @method static $this push() Send data to devpilot
 * @method static void log(string $message, array $data = []) Log data in the configured log channel for activity tracker
 *
 */
class ActivityTracker extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return self::class;
    }
}
