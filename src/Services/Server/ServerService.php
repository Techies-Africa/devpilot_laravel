<?php

namespace TechiesAfrica\Devpilot\Services\Server;

use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\BaseService;

class ServerService extends BaseService
{
    public function execute(array $commands = [])
    {
        $url = UrlConstants::executeAppCommands();
        $data = [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "commands" => $commands,
        ];
        $process = $this->guzzle->post($url , $data);
        $this->guzzle->validateResponse($process);
        return $process;
    }

}
