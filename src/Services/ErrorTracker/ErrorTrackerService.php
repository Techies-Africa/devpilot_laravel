<?php

namespace TechiesAfrica\Devpilot\Services\ErrorTracker;

use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\ErrorTypes;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Request\BasicResolver;
use Throwable;

class ErrorTrackerService extends BaseTrackerService
{
    protected BasicResolver $resolver;

    protected function setException(Throwable $exception)
    {
        $this->preRequest();
        $this->exception = $exception;
        $this->backtrace = debug_backtrace();
        $this->postRequest();
        return $this;
    }

    private function postRequest()
    {
        $user = $this->request->user();
        $this->user = empty($user) ? [] : $this->mapUserData($user);
        $this->setSeverity(ErrorTypes::getSeverity($this->exception->getCode()));
        return $this;
    }

    /**
     * Build the payload from error data.
     *
     * @return array
     */
    private function build()
    {

        $exception = $this->exception;
        $request_data = $this->resolver->resolve()->toArray();
        $platform_data = [
            "hostname" => $this->getHostname(),
            "language" => "PHP",
            "language_version" => PHP_VERSION,
            "framework" => "Laravel",
            "framework_version" => app()->version(),
        ];

        return [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "event_uuid" => $this->getEventUUID(),
            "callback_url" => $this->getErrorTrackerCallbackUrl(),
            "configuration" => [
                "project_path" => $this->getErrorTrackerProjectPath(),
                "send_code" => $this->shouldErrorTrackerSendCode(),
            ],
            "payload" => [
                "ip_address" => $this->ip_address,
                "server" => $this->server,
                "user" => $this->user,
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "message" => $exception->getMessage(),
                "stack_trace" => $this->getStackTraceContexts(),
                "code" => $exception->getCode(),
                "class_name" => get_class($exception),
                "metadata" => $this->getMetadata(),
                "request" => $request_data,
                "breadcrumbs" => $this->getBreadcrumbs(),
                "tags" => $this->getTags(),
                "unhandled"  => $this->isUnhandled(),
                "severity" => $this->getSeverity(),
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
    public function notify(Throwable $exception)
    {
        try {
            if (!$this->canPush()) {
                return null;
            }

            $this->setException($exception);
            $url = UrlConstants::logError();
            $data = $this->build();
            $process = $this->guzzle->post($url, $data);
            $this->guzzle->validateResponse($process);
            $this->logResponse($process["message"], $process["data"] ?? []);
            $this->response_data = $process;
            return $this;
        } catch (\Throwable $th) {
            throw $th;
            $this->logResponse($th->getMessage(), $th->getTrace());
        }
    }

    /**
     * Extract file informations from stack traces.
     *
     * @return array
     */
    private function getStackTraceContexts()
    {
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

            if(!$this->shouldErrorTrackerSendCode()){
                $context = null;
            }

            $contexts[] = [
                "file" => $this->stripPath($file_path),
                "line" => $line,
                "context" => $context,
                "class" => $trace["class"] ?? null,
                "type" => $trace["type"] ?? null,
                "function" => $trace["function"] ?? null,
            ];
        }
        return base64_encode(json_encode($contexts));
        // return $contexts;
    }

    private function stripPath($file_path)
    {
        return str_replace($this->getErrorTrackerStripPath(), "", $file_path);
    }

    private function projectPath($file_path)
    {
        return str_replace($this->getErrorTrackerProjectPath(), "", $file_path);
    }
}
