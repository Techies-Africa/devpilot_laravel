<?php

namespace TechiesAfrica\Devpilot\Services\Env;

use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\BaseService;

class EnvService extends BaseService
{
    public function load(string $filename = ".env")
    {
        $url = UrlConstants::loadEnv();
        $data = [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "filename" => $filename,
        ];
        $process = $this->guzzle->post($url , $data);
        $this->guzzle->validateResponse($process);
        return $process;
    }

    public function save(string $content, string $filename = ".env")
    {
        $url = UrlConstants::saveEnv();
        $data = [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "filename" => $filename,
            "content" => $content
        ];
        $process = $this->guzzle->post($url , $data);
        $this->guzzle->validateResponse($process);
        return $process;
    }
}
