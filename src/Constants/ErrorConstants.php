<?php

namespace TechiesAfrica\Devpilot\Constants;

class ErrorConstants
{
    const SEVERITY_WARNING = "warning";
    const SEVERITY_INFO = "info";
    const SEVERITY_ERROR = "error";
    const SERVERITY_OPTIONS = [
        self::SEVERITY_ERROR,
        self::SEVERITY_INFO,
        self::SEVERITY_WARNING,
    ];
}
