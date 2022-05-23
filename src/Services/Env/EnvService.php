<?php

namespace TechiesAfrica\Devpilot\Services\Env;

use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\BaseService;
use TechiesAfrica\Devpilot\Services\General\Guzzle\GuzzleService;

class EnvService extends BaseService
{
    public function load(string $filename = ".env")
    {
        $url = UrlConstants::loadEnv();
        $data = [
            "user_access_token" => $this->user_access_token,
            "passphrase" => $this->passphrase,
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "filename" => $filename,
        ];
        $guzzle = new GuzzleService($url);
        $process = $guzzle->post($data);
        $guzzle->validateResponse($process);
        return $process;
    }

    public function save(string $content, string $filename = ".env")
    {
        $url = UrlConstants::saveEnv();
        $data = [
            "user_access_token" => $this->user_access_token,
            "passphrase" => $this->passphrase,
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "filename" => $filename,
            "content" => $content
        ];
        $guzzle = new GuzzleService($url);
        $process = $guzzle->post($data);
        $guzzle->validateResponse($process);
        return $process;
    }

}
