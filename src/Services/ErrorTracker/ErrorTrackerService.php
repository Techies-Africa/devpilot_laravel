<?php

namespace TechiesAfrica\Devpilot\Services\ErrorTracker;

use Exception;
use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\ErrorTypes;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Request\BasicResolver;

class ErrorTrackerService extends BaseTrackerService
{
    protected BasicResolver $resolver;

    public function __construct(Exception $exception)
    {
        parent::__construct();
        $this->resolver = new BasicResolver;
        $this->preRequest();
        $this->exception = $exception;
        $this->postRequest();
    }


    public function postRequest()
    {
        $user = $this->request->user();
        $this->user = empty($user) ? [] : $this->mapUserData($user);
        return $this;
    }

    /**
     * Build the payload from error data.
     *
     * @return array
     */
    public function build()
    {
        $exception = $this->exception;
        $request_data = $this->resolver->resolve()->toArray();
        $platform_data = [
            "hostname" => $this->getHostname(),
            "language" => "PHP",
            "framework" => "Laravel",
            "runtime_version" => app()->version(),
        ];

        return [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "event_uuid" => $this->getEventUUID(),
            "callback_url" => config("devpilot.error_tracker_callback_url"),
            "payload" => [
                "ip_address" => $this->ip_address,
                "server" => $this->server,
                "user" => $this->user,
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "message" => $exception->getMessage(),
                "trace_context" => $this->getStackTraceContexts(),
                "stack_trace" => $exception->getTraceAsString(),
                "code" => $exception->getCode(),
                "class_name" => get_class($exception),
                "meta_data" => $this->getMetaData(),
                "request" => $request_data,
                "breadcrumbs" => $this->getBreadcrumbs(),
                "tags" => $this->getTags(),
                "unhandled"  => $this->isUnhandled(),
                "severity" => $this->getSeverity() ?? ErrorTypes::getSeverity($exception->getCode()),
                "platform" => $platform_data,
                "custom_tabs" => $this->getCustomTabs(),
            ],
        ];
    }

    /**
     * Send the payload to Devpilot.
     *
     * @return $this
     */
    public function push()
    {
        try {
            if (!$this->canPush()) {
                return null;
            }
            $url = UrlConstants::logError();
            $data = $this->build();
            dd($data);
            $process = $this->guzzle->post($url, $data);
            $this->guzzle->validateResponse($process);
            $this->logResponse($process["message"], $process["data"] ?? []);
            $this->response_data = $process;
            dd($process);
            return $this;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Extract file informations from stack traces.
     *
     * @return array
     */
    public function getStackTraceContexts()
    {
        // dd($this->exception->getFile());
        $traces = $this->exception->getTrace();
        $contexts = [];
        foreach ($traces as $trace) {
            $file_path = $trace["file"] ?? null;
            if (empty($file_path)) {
                continue;
            }

            $line = $trace["line"];
            $line_before = $line - 4;
            $line_after = $line + 4;

            $file_content = explode("\n", file_get_contents($file_path));
            if (count($file_content) < $line_after) {
                $line_after = count($file_content);
            }

            $context = [];
            for ($i = $line_before; $i < $line_after; $i++) {
                if (!empty($value = $file_content[$i - 1] ?? null)) {
                    $context[$i] = $value;
                }
            }

            $contexts[] = [
                "file" => $file_path,
                "line" => $line,
                "context" => $context,
                "class" => $trace["class"] ?? null,
                "type" => $trace["type"] ?? null,
                "function" => $trace["function"] ?? null,
            ];
        }
        return $contexts;
    }
}
