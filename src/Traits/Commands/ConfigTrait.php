<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;

trait ConfigTrait
{
    public function hasWorkingDirectory($throw_error = true): bool
    {
        $exists = file_exists(base_path(".devpilot"));
        if ($throw_error && !$exists) {
            throw new ServerErrorException("The .devpilot folder is missing in your project directory. Kindly run php artisan devpilot:install.");
        }
        return $exists;
    }
}
