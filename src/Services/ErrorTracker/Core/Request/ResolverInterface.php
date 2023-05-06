<?php

namespace TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Request;

interface ResolverInterface
{
    /**
     * Resolve the current request.
     *
     * @return \TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Request\RequestInterface
     */
    public function resolve();
}
