<?php

namespace TechiesAfrica\Devpilot\Services\Deployments;

use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\BaseService;

class DeploymentService extends BaseService
{
    protected bool $enable_deployment_logging = false;

    public function __construct()
    {
        $this->setEnableLogging($this->isDeploymentLoggingEnabled());
        parent::__construct();
    }

    public function setEnableLogging(bool $value)
    {
        $this->enable_deployment_logging = $value;
        return $this;
    }

    public static function log(string $message, array $data = []): void
    {
        (new DeploymentService)->logResponse($message, $data);
    }

    public function logResponse(string $message, array $data = []): void
    {
        if ($this->enable_deployment_logging) {
            $this->logger($message, $data, $this->getDeploymentLogChannel());
        }
    }

    public function deploy(array $options = [])
    {

        $url = UrlConstants::deploy();
        $data = [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "branch" => $options["branch"] ?? null,
            "hooks" => $options["hooks"] ?? null,
            "commands" => $options["commands"] ?? null,
            "storage_paths" => $options["storage_paths"] ?? null,
            "hostname" => $this->getHostname(),
            "git_user" => self::tryOrNull("git config user.name"),
            "git_email" => self::tryOrNull("git config user.email"),
        ];

        $process = $this->guzzle->post($url, $data);
        $this->guzzle->validateResponse($process);
        $this->logResponse($process["message"], $process["data"] ?? []);
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

    private static function tryOrNull($command)
    {
        try {
            return shell_exec($command);
        } catch (\Throwable $th) {
            return null;
        }
    }
}
