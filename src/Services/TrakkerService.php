<?php

namespace TechiesAfrica\LaravelTrakker\Services;

use Illuminate\Http\Request;

class TrakkerService extends BaseTrakkerService
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
            "app_key" => env("TRAKKER_KEY"),
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

        $url = env("TRAKKER_URL")."/api/visits/log";
        $data = $this->build();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($data),
        ));

        $process = curl_exec($curl);
        curl_close($curl);
        TrakkerService::log("pushed data" , [$process , $data]);

        return $process;
    }
}
