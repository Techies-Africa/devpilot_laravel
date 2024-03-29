<?php

namespace TechiesAfrica\Devpilot\Services\ActivityTracker;

use Illuminate\Http\Request;
use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Exceptions\ActivityTracker\ActivityTrackerException;

class ActivityTrackerService extends BaseTrackerService
{
    public static function init(): self
    {
        return app(self::class);
    }

    public function postRequest(Request $request)
    {
        $this->request = $request;
        $user = $request->user();
        $action = optional($request->route())->action;
        if (empty($action)) {
            $this->checkIfVerbose(new ActivityTrackerException("No route action found."));
            $this->can_log = false;
            return $this;
        }

        $is_ignored = RouteCheckService::checkIfRouteIsIgnored($request->route(), $this->ignore_routes);
        if ($is_ignored) {
            $this->checkIfVerbose(new ActivityTrackerException("No route action found."));
            $this->can_log = false;
            return $this;
        }

        $is_ignored = RouteCheckService::checkIfMiddlewareIsIgnored($request->route(), $this->ignore_middlewares);

        if ($is_ignored) {
            $this->checkIfVerbose(new ActivityTrackerException("No route action found."));
            $this->can_log = false;
            return $this;
        }

        $this->user = empty($user) ? [] : $this->mapUserData($user);
        $this->route = [
            "url" => $request->fullUrl(),
            "action" => $action,
            "method" => $request->getMethod(),
            "referrer" => $request->headers->get('referer'),
            "page_type" => RouteCheckService::getPageType(
                $request->route(),
                $this->authenticated_middlewares,
            )
        ];
        $this->response_time = now();
        return $this;
    }


    public function build()
    {
        return [
            "app_key" => $this->app_key,
            "app_secret" => $this->app_secret,
            "request_time" => $this->request_time,
            "response_time" => $this->response_time,
            "callback_url" => $this->getActivityTrackerCallbackUrl(),
            "payload" => [
                "ip_address" => $this->ip_address,
                "server" => $this->server,
                "middlewares" => implode(",", $this->request->route()->action["middleware"] ?? []),
                "route" => $this->route,
                "user" => $this->user,
                "metadata" => $this->metadata
            ],
        ];
    }

    function isTest(bool $value = true)
    {
        $this->is_test = $value;
        return $this;
    }

    public function push()
    {
        if (!$this->canPush()) {
            return null;
        }

        $url = UrlConstants::logActivity();
        $data = $this->build();

        if ($this->is_test) {
            $data["is_test"] = 1;
        }

        $process = $this->guzzle->post($url, $data);
        $this->guzzle->validateResponse($process);
        $this->logResponse($process["message"], $process["data"] ?? []);
        if (!empty($data = $process)) {
            $this->setResponseData($data);
        }
        $this->onResponse();
        return $this;
    }
}
