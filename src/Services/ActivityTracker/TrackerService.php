<?php

namespace TechiesAfrica\Devpilot\Services\ActivityTracker;

use Illuminate\Http\Request;
use TechiesAfrica\Devpilot\Constants\UrlConstants;
use TechiesAfrica\Devpilot\Services\General\Guzzle\GuzzleService;

class TrackerService extends BaseTrackerService
{

    public static function tracker(): self
    {
        return app(self::class);
    }

    public function postRequest(Request $request)
    {
        $this->request = $request;
        $user = $request->user();
        $action = optional($request->route())->action;

        if (empty($action)) {
            $this->can_log = false;
            return $this;
        }

        $is_ignored = RouteCheckService::checkIfRouteIsIgnored($request->route(), $this->ignore_routes);

        if ($is_ignored) {
            $this->can_log = false;
            return $this;
        }

        $is_ignored = RouteCheckService::checkIfMiddlewareIsIgnored($request->route(), $this->ignore_middlewares);

        if ($is_ignored) {
            $this->can_log = false;
            return $this;
        }

        $this->user = empty($user) ? null : $this->mapUserData($user);
        $this->route = [
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
            "payload" => [
                "ip_address" => $this->ip_address,
                "server" => $this->server,
                "middlewares" => implode(",", $this->request->route()->action["middleware"] ?? []),
                "route" => $this->route,
                "user" => $this->user,
                "extra_data" => $this->extra_data
            ],
            "success_callback_url" => null,
            "error_callback_url" => null,
        ];
    }


    public function push()
    {
        if (!$this->canPush()) {
            return null;
        }

        $url = UrlConstants::logActivity();
        $data = $this->build();

        $process = $this->guzzle->post($url , $data);
        $this->guzzle->validateResponse($process);
        return $this;
    }
}
