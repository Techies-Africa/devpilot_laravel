<?php

namespace TechiesAfrica\Devpilot\Services\ActivityTracker;

use Closure;
use Illuminate\Http\Request;
use TechiesAfrica\Devpilot\Exceptions\ActivityTracker\ActivityTrackerException;
use TechiesAfrica\Devpilot\Services\BaseService;
use Throwable;

class BaseTrackerService extends BaseService
{
    protected bool $is_test = false;
    protected bool $can_log = true;
    protected bool $should_log = true;
    protected bool $enable_activity_tracker_logging = false;
    protected $route = [];
    protected $request_time;
    protected $response_time;
    protected $ignore_routes = [];
    protected $ignore_middlewares = ["Barryvdh\Debugbar\Middleware\DebugbarEnabled"];
    protected $authenticated_middlewares = ["auth", "admin", "verified"];
    protected $response_data = null;
    protected $response_callback;


    public function __construct()
    {
        $this->setShouldLog($this->isActivityTrackerEnabled());
        $this->setEnableLogging($this->isActivityTrackerLoggingEnabled());
        $this->setIgnoreRoutes($this->getActivityTrackerIgnoreRoutes() ?? []);
        $this->setAuthenticatedMiddlewares($this->getActivityTrackerAuthenticatedMiddlewares() ?? []);
        $this->setUserFields($this->getActivityTrackerUserFields() ?? []);
        parent::__construct();
    }

    public function preRequest(Request $request)
    {
        $this->request = $request;
        $this->server = $this->filterServerData($request->server());
        $this->headers = $request->headers->all();
        $this->request_time = now();
        $this->ip_address = $request->ip();
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


    public function setResponseData(array $data = null)
    {
        $this->response_data = $data;
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
        if (empty($this->getAuthenticationAppKey()) || empty($this->getGeneralBaseUrl())) {
            return $this->checkIfVerbose(
                new ActivityTrackerException("Devpilot app keys or base url not configured properly."),
                false
            );
        }
        if ((!$this->should_log || !$this->can_log)) {
            return $this->checkIfVerbose(
                new ActivityTrackerException("Devpilot activity tracking disabled."),
                false
            );
        }
        if ($this->isAjax()) {
            return $this->checkIfVerbose(
                new ActivityTrackerException("Devpilot activity tracking skipped ajax request."),
                false
            );
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
            $this->logger($message, $data, $this->getActivityTrackerLogChannel());
        }
    }

    public function setResponseCallback(Closure $callback)
    {
        $this->response_callback = $callback;
        return $this;
    }

    public function onResponse(): void
    {
        array_map($this->response_callback, [$this->response_data]);
    }
}

