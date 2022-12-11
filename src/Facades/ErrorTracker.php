<?php

namespace TechiesAfrica\Devpilot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static $this notify(\Throwable $exception)
 * @method static $this setSeverity(string $severity)
 * @method static string getSeverity()
 * @method static bool isUnhandled()
 * @method static $this addMetadata(string $key,string|array $value)
 * @method static $this setMetadata(array $data)
 * @method static $this removeMetadata(string $key)
 * @method static $this clearMetadata()
 * @method static array getMetadata()
 * @method static $this addTag(string $key, string|null $value = null)
 * @method static $this setTags(array $data)
 * @method static $this removeTag(string $key)
 * @method static $this clearTags()
 * @method static array getTags()
 * @method static $this addCustomTab(string $key, string $title,  array|null $data = null)
 * @method static $this removeCustomTab(string $key)
 * @method static $this clearCustomTabs()
 * @method static array getCustomTabs()
 * @method static $this addBreadcrumb(string $name,string|null $type = null, array|null $metadata = []) Add new breacrumb to error
 * @method static $this clearBreadcrumbs() Remove all saved breadcrumbs for the error
 * @method static $this getBreadcrumbs() Get the breadcrumbs of the error.
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
