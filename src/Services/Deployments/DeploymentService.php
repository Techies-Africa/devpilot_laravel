<?php

namespace TechiesAfrica\Devpilot\Services\Deployments;

use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\BaseService;

class DeploymentService extends BaseService
{
    public function deploy(array $options = [])
    {

        $url = UrlConstants::deploy();
        $data = [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "branch" => $options["branch"] ?? null,
            "hooks" => $options["hooks"] ?? null,
        ];
        $process = $this->guzzle->post($url, $data);
        $this->guzzle->validateResponse($process);
        return $process;
    }

    public function information($deployment_id)
    {
        $url = UrlConstants::deploymentInformation();
        $data = [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "deployment_id" => $deployment_id,
        ];
        $process = $this->guzzle->post($url, $data);
        $this->guzzle->validateResponse($process);
        return $process;
    }
}
