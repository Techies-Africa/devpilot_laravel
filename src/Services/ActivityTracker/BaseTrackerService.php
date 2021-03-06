<?php

namespace TechiesAfrica\Devpilot\Services\ActivityTracker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TechiesAfrica\Devpilot\Services\BaseService;
use Throwable;

class BaseTrackerService extends BaseService
{
    protected Request $request;
    protected bool $can_log = true;
    protected bool $should_log;
    protected bool $enable_activity_tracker_logging = false;
    protected $server = [];
    protected $headers = [];
    protected $route = [];
    protected $extra_data;
    protected $request_time;
    protected $response_time;
    protected $ip_address;
    protected $user;
    protected $user_fields = ["id" => "id", "name" => "name", "email" => "email"];
    protected $ignore_routes = [];
    protected $ignore_middlewares = ["Barryvdh\Debugbar\Middleware\DebugbarEnabled"];
    protected $authenticated_middlewares = ["auth", "admin", "verified"];
    public $response_data;


    public function __construct()
    {
        $this->setShouldLog(config("devpilot.enable_activity_tracking", true));
        $this->setEnableLogging(config("devpilot.enable_activity_tracker_logging", false));
        parent::__construct();
    }

    public function preRequest(Request $request)
    {
        $this->request = $request;
        $this->server = $request->server();
        $this->headers = $request->headers->all();
        $this->request_time = now();
        $this->ip_address = $request->ip();
        return $this;
    }

    public function mapUserData($user)
    {
        $fields = $this->user_fields;
        if (count($fields) == 0) {
            return null;
        }

        $data = [];
        foreach ($fields as $key => $value) {
            $data[$key] = $user->$value;
        }
        return $data;
    }

    public function setUserFields(array $data)
    {
        $this->user_fields = $data;
        return $this;
    }

    public function setExtraData(array $data)
    {
        $this->extra_data = $data;
        return $this;
    }


    public function setIgnoreRoutes(array $data)
    {
        $this->ignore_routes = $data;
        return $this;
    }

    public function setIgnoreMiddlewares(array $data)
    {
        $this->ignore_middlewares = $data;
        return $this;
    }

    public function setAuthenticatedMiddlewares(array $data)
    {
        $this->authenticated_middlewares = $data;
        return $this;
    }

    public function setShouldLog(bool $value)
    {
        $this->should_log = $value;
        return $this;
    }

    public function setEnableLogging(bool $value)
    {
        $this->enable_activity_tracker_logging = $value;
        return $this;
    }


    public function isAjax()
    {
        try {
            $middlewares = $this->request->route()->action["middleware"] ?? ["web"];
            if ($this->request->wantsJson()  && in_array("web", $middlewares)) {
                return true;
            }
        } catch (Throwable $e) {
        }
        return false;
    }

    public function canPush()
    {
        if (empty(config("devpilot.app_key")) || empty(config("devpilot.base_url"))) {
            return false;
        }
        if (!$this->should_log || !$this->can_log) {
            return false;
        }
        if ($this->isAjax()) {
            return false;
        }
        return true;
    }

    public static function log(string $message, array $data = []): void
    {
        (new BaseTrackerService)->logResponse($message, $data);
    }

    public function logResponse(string $message, array $data = []): void
    {
        if ($this->enable_activity_tracker_logging) {
            $this->logger($message , $data , config("devpilot.activity_tracker_log"));
        }
    }
}
