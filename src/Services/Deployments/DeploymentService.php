<?php

namespace TechiesAfrica\Devpilot\Services\Deployments;

use Exception;
use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\General\Guzzle\GuzzleService;
use Throwable;

class DeploymentService extends BaseDeploymentService
{
    public function deploy(array $options = [])
    {

        $url = UrlConstants::get(UrlConstants::LOG_ACTIVITY);
        $data = [
            "user_access_token" => $this->user_access_token,
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "branch" => $options["branch"] ?? null,
            "hooks" => $options["hooks"] ?? null,

        ];

        $guzzle = new GuzzleService($url);
        $process = $guzzle->post($data);
        $guzzle->validateResponse($process);
    }
}
