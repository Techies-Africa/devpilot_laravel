<?php

namespace TechiesAfrica\Devpilot\Services\General\Guzzle;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use TechiesAfrica\Devpilot\Exceptions\General\GuzzleException;
use Throwable;

class GuzzleService
{
    public string $url;
    public array $headers;
    public Client $client;

    public function __construct(string $url, array $headers = [])
    {
        $this->url = $url;
        $this->headers = array_merge(
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Api-Version' => 'v1',
            ],
            $headers
        );
        $this->client = new Client(['verify' => false]);
    }


    public function post(array $data = [])
    {
        try {
            $response = $this->client->post(
                $this->url,
                [
                    'headers' => $this->headers,
                    'json' => $data,
                ]
            );
            return $this->success($response);
        } catch (Throwable $e) {
            return $this->error($e);
        }
    }

    public function get(array $data = [])
    {
        try {
            $response = $this->client->get(
                $this->url,
                [
                    'headers' => $this->headers,
                    'json' => $data,
                ]
            );
            return $this->success($response);
        } catch (Throwable $e) {
            return $this->error($e);
        }
    }

    public function delete()
    {
        try {
            $response = $this->client->delete(
                $this->url,
                [
                    'headers' => $this->headers,
                ]
            );
            return $this->success($response);
        } catch (Throwable $e) {
            return $this->error($e);
        }
    }

    private function success($response)
    {
        $body = $response->getBody();
        return self::response(
            $response->getReasonPhrase(),
            $response->getStatusCode(),
            (json_decode((string) $body, true))
        );
    }

    private function error(Throwable $e)
    {
        try {
            throw $e;
        } catch (BadResponseException $e) {
            return self::response($e->getResponse()->getBody()->getContents(), $e->getCode());
        } catch (Throwable $e) {
            return self::response($e->getMessage(), $e->getCode());
        }
    }

    private function response($message, $status, $data = null)
    {
        return [
            "status" => $status,
            "message" => $message,
            "data" => $data
        ];
    }

    public function validateResponse(array $process)
    {
        if ($process["status"] == 0) {
            throw new GuzzleException("Unable to connect to remote server. Kindly check your internet connection and retry.");
        }
    }
}
