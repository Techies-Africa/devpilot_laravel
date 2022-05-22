<?php

namespace TechiesAfrica\Devpilot\Services\Server;

use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\BaseService;
use TechiesAfrica\Devpilot\Services\General\Guzzle\GuzzleService;

class ServerService extends BaseService
{
    public function execute(array $commands = [])
    {
        $url = UrlConstants::executeAppCommands();
        $data = [
            "user_access_token" => $this->user_access_token,
            "passphrase" => $this->passphrase,
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "commands" => $commands,
        ];
        $guzzle = new GuzzleService($url);
        $process = $guzzle->post($data);
        $guzzle->validateResponse($process);
        return $process;
    }

}
