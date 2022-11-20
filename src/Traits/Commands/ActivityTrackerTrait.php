<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Services\ActivityTracker\ActivityTrackerService;
use TechiesAfrica\Devpilot\Services\General\Guzzle\GuzzleService;
use TechiesAfrica\Devpilot\Traits\Commands\Errors\ErrorHandlerTrait;

trait ActivityTrackerTrait
{
    use ErrorHandlerTrait;
    // public function test()
    // {
    //     $guzzle = new GuzzleService(route("devpilot.activity_tracker.test"));
    //     $process = $guzzle->get();
    //     dd($process);
    //     $guzzle->validateResponse($process);
    //     if ($process["status"] != 200) {
    //         $this->handleErrors($process);
    //     }
    // }


    public function test()
    {
        $tracker = new ActivityTrackerService;
        if (!$tracker->canPush()) {
            throw new ServerErrorException("Cannot push data to server. Kindly check that all configurations are set properly!");
        }
        $payload = $this->buildPayload();
        $guzzle = new GuzzleService();
        $process = $guzzle->post(UrlConstants::logActivity() , $payload);
        $guzzle->validateResponse($process);
        if ($process["status"] != 200) {
            $this->handleErrors($process);
        }
    }

    public function buildPayload()
    {
        $app_url = env("APP_URL");
        $url = parse_url($app_url);
        return [
            "user_access_token" => config("devpilot.user_access_token"),
            "app_key" => config("devpilot.app_key"),
            "app_secret" => config("devpilot.app_secret"),
            "request_time" => now(),
            "response_time" => now()->addSeconds(10),
            "payload" => [
                "ip_address" => "127.0.0.1",
                "server" => [
                    "DOCUMENT_ROOT" => base_path("public"),
                    "REMOTE_ADDR" => "127.0.0.1",
                    "REMOTE_PORT" => "57834",
                    "SERVER_SOFTWARE" => "PHP 7.4.13 Development Server",
                    "SERVER_PROTOCOL" => "HTTP/1.1",
                    "SERVER_NAME" => $url["host"],
                    "SERVER_PORT" => $url["port"],
                    "REQUEST_URI" => "/",
                    "REQUEST_METHOD" => "GET",
                    "SCRIPT_NAME" => "index.php",
                    "SCRIPT_FILENAME" =>  base_path("public\\index.php"),
                    "PHP_SELF" => "index.php",
                    "HTTP_HOST" => "127.0.0.1:5007",
                    "HTTP_CONNECTION" => "keep-alive",
                    "HTTP_CACHE_CONTROL" => "max-age=0",
                    "HTTP_SEC_CH_UA" => 'Not A;Brand";v="99", "Chromium";v="96", "Google Chrome";v="96',
                    "HTTP_SEC_CH_UA_MOBILE" => "?0",
                    "HTTP_SEC_CH_UA_PLATFORM" => "Windows",
                    "HTTP_DNT" => "1",
                    "HTTP_UPGRADE_INSECURE_REQUESTS" => "1",
                    "HTTP_USER_AGENT" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36",
                    "HTTP_ACCEPT" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                    "HTTP_SEC_FETCH_SITE" => "same-origin",
                    "HTTP_SEC_FETCH_MODE" => "navigate",
                    "HTTP_SEC_FETCH_USER" => "?1",
                    "HTTP_SEC_FETCH_DEST" => "document",
                    "HTTP_ACCEPT_ENCODING" => "gzip, deflate, br",
                    "HTTP_ACCEPT_LANGUAGE" => "en-US,en;q=0.9",
                    "HTTP_COOKIE" => "_hjid=3855e468-06fc-4e41-8bfa-19743ae799f1; _hjSessionUser_1070954=eyJpZCI6IjY5NTBmZTRmLWMyY2EtNWI3Ny1iODk3LTBkMjlhZTE3NjBlNiIsImNyZWF0ZWQiOjE2Mzc3MDQyMjkxMTYsImV4aXN0aW5nIjp0cnVlfQ==",
                    "REQUEST_TIME_FLOAT" => 1638883301.2886,
                    "REQUEST_TIME" => 1638883301,
                ],
                "headers" => [
                    "host" => [
                        0 => "127.0.0.1:5007"
                    ],
                    "connection" => [
                        0 => "keep-alive"
                    ],
                    "cache-control" => [
                        0 => "max-age=0"
                    ],
                    "sec-ch-ua" => [
                        0 => 'Not A;Brand";v="99", "Chromium";v="96", "Google Chrome";v="96'
                    ],
                    "sec-ch-ua-mobile" => [
                        0 => "?0"
                    ],
                    "sec-ch-ua-platform" => [
                        0 => "Windows"
                    ],
                    "dnt" => [
                        0 => "1"
                    ],
                    "upgrade-insecure-requests" => [
                        0 => "1"
                    ],
                    "user-agent" => [
                        0 => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36"
                    ],
                    "accept" => [
                        0 => "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"
                    ],
                    "sec-fetch-site" => [
                        0 => "same-origin"
                    ],
                    "sec-fetch-mode" => [
                        0 => "navigate"
                    ],
                    "sec-fetch-user" => [
                        0 => "?1"
                    ],
                    "sec-fetch-dest" => [
                        0 => "document"
                    ],
                    "accept-encoding" => [
                        0 => "gzip, deflate, br"
                    ],
                    "accept-language" => [
                        0 => "en-US,en;q=0.9"
                    ],
                    "cookie" => [
                        0 => "_hjid=3855e468-06fc-4e41-8bfa-19743ae799f1; _hjSessionUser_1070954=eyJpZCI6IjY5NTBmZTRmLWMyY2EtNWI3Ny1iODk3LTBkMjlhZTE3NjBlNiIsImNyZWF0ZWQiOjE2Mzc3MDQyMjkxMTYsImV4aXN0aW5nIjp0cnVlfQ=="
                    ],
                ],
                "route" => [
                    "action" => [
                        "middleware" => [
                            0 => "web"
                        ],
                        "uses" => "TechiesAfrica\\Devpilot\\Http\Controllers\\ActivityTrackerController@test",
                        "controller" => "TechiesAfrica\\Devpilot\\Http\Controllers\\ActivityTrackerController",
                        "as" => "devpilot.activity_tracker.test",
                        "namespace" => "TechiesAfrica\\Devpilot\\Http\Controllers",
                        "prefix" => null,
                        "where" => [],
                    ],
                    "method" => "GET",
                    "referrer" => null,
                    "page_type" => "Unauthenticated",
                ],
                "user" => null,
                "meta_data" => null,
                "is_test" => 1,
            ]

        ];
    }
}
