<?php

namespace TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Request;

interface RequestInterface
{
    /**
     * Are we currently processing a request?
     *
     * @return bool
     */
    public function isRequest();

    /**
     * Get the session data.
     *
     * @return array
     */
    public function getSession();

    /**
     * Get the cookies.
     *
     * @return array
     */
    public function getCookies();

    /**
     * Get the request formatted as meta data.
     *
     * @return array
     */
    public function getMetadata();

    /**
     * Get the request context.
     *
     * @return string|null
     */
    public function getContext();

    /**
     * Get the request user id.
     *
     * @return string|null
     */
    public function getUserId();

    /**
     * Get the request as array.
     *
     * @return array
     */
    public function toArray(): array;
}
