<?php

namespace TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Breadcrumbs;

use Exception;
use InvalidArgumentException;

class Breadcrumb
{
    /**
     * The navigation type.
     *
     * @var string
     */
    const NAVIGATION_TYPE = 'navigation';

    /**
     * The request type.
     *
     * @var string
     */
    const REQUEST_TYPE = 'request';

    /**
     * The process type.
     *
     * @var string
     */
    const PROCESS_TYPE = 'process';

    /**
     * The log type.
     *
     * @var string
     */
    const LOG_TYPE = 'log';

    /**
     * The user type.
     *
     * @var string
     */
    const USER_TYPE = 'user';

    /**
     * The state type.
     *
     * @var string
     */
    const STATE_TYPE = 'state';

    /**
     * The error type.
     *
     * @var string
     */
    const ERROR_TYPE = 'error';

    /**
     * The manual type.
     *
     * @var string
     */
    const MANUAL_TYPE = 'manual';

    /**
     * The maximum size of the breadcrumb.
     *
     * @var int
     */
    const MAX_SIZE = 4096;

    /**
     * The timestamp of the breadcrumb.
     *
     * @var string
     */
    protected $timestamp;

    /**
     * The name of the breadcrumb.
     *
     * @var string
     */
    protected $name;

    /**
     * The type of the breadcrumb.
     *
     * @var string
     */
    protected $type;

    /**
     * The meta data of the breadcrumb.
     *
     * @var array
     */
    protected $metadata;

    /**
     * Create a new breadcrumb instance.
     *
     * @param string $name     the name of the breadcrumb
     * @param string $type     the type of breadcrumb
     * @param array  $metadata additional information about the breadcrumb
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function __construct($name, $type, array $metadata = [])
    {
        if (!is_string($name)) {
            if (is_null($name)) {
                $metadata['BreadcrumbError'] = 'NULL provided as the breadcrumb name';
                $name = '<no name>';
            } else {
                $metadata['BreadcrumbError'] = 'Breadcrumb name must be a string - '.gettype($name).' provided instead';
                $name = '<no name>';
            }
        } elseif ($name === '') {
            $metadata['BreadcrumbError'] = 'Empty string provided as the breadcrumb name';
            $name = '<no name>';
        }

        $types = static::getTypes();

        if (!in_array($type, $types, true)) {
            throw new Exception(sprintf('The breadcrumb type must be one of the set of %d standard types.', count($types)));
        }

        $this->timestamp = now();
        $this->name = $name;
        $this->type = $type;
        $this->metadata = $metadata;
    }

    /**
     * Get the breadcrumb as an array.
     *
     * Note that this is without the meta data.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'timestamp' => $this->timestamp,
            'name' => $this->name,
            'type' => $this->type,
            'metadata' => $this->metadata
        ];
    }

    /**
     * Get the breadcrumb meta data.
     *
     * Note that this still needs sanitizing before use.
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Get the set of valid breadrum types.
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            static::NAVIGATION_TYPE,
            static::REQUEST_TYPE,
            static::PROCESS_TYPE,
            static::LOG_TYPE,
            static::USER_TYPE,
            static::STATE_TYPE,
            static::ERROR_TYPE,
            static::MANUAL_TYPE,
        ];
    }
}
