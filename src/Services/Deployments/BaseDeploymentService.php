<?php

namespace TechiesAfrica\Devpilot\Services\Deployments;

class BaseDeploymentService
{
    public function __construct()
    {
        $this->user_access_token = config("devpilot.user_access_token");
        $this->passphrase = config("devpilot.user_access_token_passphrase");
        $this->app_key = config("devpilot.app_key");
        $this->app_secret = config("devpilot.app_secret");
    }

}
