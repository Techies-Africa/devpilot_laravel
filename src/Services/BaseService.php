<?php

namespace TechiesAfrica\Devpilot\Services;

use Illuminate\Support\Facades\Log;
use TechiesAfrica\Devpilot\Services\General\Guzzle\GuzzleService;

class BaseService
{
    public GuzzleService $guzzle;
    public function __construct()
    {
        $this->user_access_token = config("devpilot.user_access_token");
        $this->passphrase = config("devpilot.user_access_token_passphrase");
        $this->app_key = config("devpilot.app_key");
        $this->app_secret = config("devpilot.app_secret");
        $this->guzzle = new GuzzleService([
            'Api-Version' => 'v1',
            "User-Access-Token" => $this->user_access_token,
            "User-Access-Passphrase" => $this->passphrase,
        ]);
    }

    public function logger(string $message, array $data = [] , $channel = "stack"): void
    {
        Log::channel($channel)->info($message, $data);
    }
}
