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

        $url = UrlConstants::deploy();
        $data = [
            "user_access_token" => $this->user_access_token,
            "passphrase" => $this->passphrase,
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "branch" => $options["branch"] ?? null,
            "hooks" => $options["hooks"] ?? null,
        ];
        $guzzle = new GuzzleService($url);
        $process = $guzzle->post($data);
        $guzzle->validateResponse($process);
        return $process;
    }

    public function information($deployment_id)
    {
        $url = UrlConstants::deploymentInformation();
        $data = [
            "user_access_token" => $this->user_access_token,
            "passphrase" => $this->passphrase,
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "deployment_id" => $deployment_id,
        ];
        $guzzle = new GuzzleService($url);
        $process = $guzzle->post($data);
        $guzzle->validateResponse($process);
        return $process;
    }
}
