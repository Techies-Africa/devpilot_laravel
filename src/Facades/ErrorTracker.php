<?php

namespace TechiesAfrica\Devpilot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static $this notify(\Throwable $exception)
 * @method static $this setSeverity(string $severity)
 * @method static string getSeverity()
 * @method static bool isUnhandled()
 * @method static $this addMetaData(string $key,string|array $value)
 * @method static $this setMetaData(array $data)
 * @method static $this removeMetaData(string $key)
 * @method static $this clearMetaData()
 * @method static array getMetaData()
 * @method static $this addTag(string $key, string $value = null)
 * @method static $this setTags(array $data)
 * @method static $this removeTag(string $key)
 * @method static $this clearTags()
 * @method static array getTags()
 * @method static $this addCustomTab(string $key, string $title,  array $data)
 * @method static $this removeCustomTab(string $key)
 * @method static $this clearCustomTabs()
 * @method static array getCustomTabs()
 *
 */
class ErrorTracker extends Facade
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
