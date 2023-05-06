<?php

namespace TechiesAfrica\Devpilot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static $this notify(\Throwable $exception) Send the error to Devpilot
 * @method static $this setSeverity(string $severity) Set the severity of the exception
 * @method static string getSeverity() Get the severity of the exception
 * @method static bool isUnhandled() Check if exception is handled or not
 * @method static $this addMetadata(string $key,string|array $value) Add extra data for this exception
 * @method static $this setMetadata(array $data) Override existing metadata
 * @method static $this removeMetadata(string $key) Remove extra data for this exception by key
 * @method static $this clearMetadata() Clear all metadata for this exception
 * @method static array getMetadata() Get added metadata
 * @method static $this addTag(string $key, string|null $value = null) Add a tag
 * @method static $this setTags(array $data) Override existing tags
 * @method static $this removeTag(string $key) Remove a tag by key
 * @method static $this clearTags() Remove all tags
 * @method static array getTags() Get added tags
 * @method static $this addCustomTab(string $key, string $title,  array|null $data = null) Add a custom tab
 * @method static $this removeCustomTab(string $key) Remove a custom tab by key
 * @method static $this clearCustomTabs() Clear all custom tabs
 * @method static array getCustomTabs() Get added custom tabs
 * @method static $this addBreadcrumb(string $name,string|null $type = null, array|null $metadata = []) Add new breacrumb to error
 * @method static $this clearBreadcrumbs() Remove all saved breadcrumbs for the error
 * @method static $this getBreadcrumbs() Get the breadcrumbs of the error.
 * @method static $this verbose(bool $value = false) Throw any internal errors.
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
