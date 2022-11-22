<?php

namespace TechiesAfrica\Devpilot\Services\ErrorTracker;

use Exception;
use InvalidArgumentException;
use TechiesAfrica\Devpilot\Constants\ErrorConstants;
use TechiesAfrica\Devpilot\Services\BaseService;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Breadcrumbs\Breadcrumb;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Breadcrumbs\Recorder;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\BacktraceProcessor;


class BaseTrackerService extends BaseService
{
    protected Exception $exception;
    protected bool $can_log = true;
    protected bool $should_log;
    protected bool $enable_error_tracker_logging = false;
    protected $route = [];
    protected array $response_data;
    protected string $severity;
    protected array $tags = [];
    protected array $custom_tabs = [];
    protected Recorder $breadcrub_recorder;
    protected array $backtrace;


    public function __construct()
    {
        $this->setShouldLog(config("devpilot.enable_error_tracking", true));
        $this->setEnableLogging(config("devpilot.enable_error_tracker_logging", false));
        parent::__construct();
        $this->level = "error";
        $this->breadcrub_recorder = new Recorder;
    }

    protected function preRequest()
    {
        $request = request();
        // dd($request);
        $this->request = $request;
        $this->server = $this->filterServerData($request->server());
        $this->headers = $request->headers->all();
        $this->ip_address = $request->ip();
        return $this;
    }


    protected function setException(Exception $exception)
    {
        $this->exception = $exception;
        return $this;
    }

    protected function setShouldLog(bool $value)
    {
        $this->should_log = $value;
        return $this;
    }

    protected function setEnableLogging(bool $value)
    {
        $this->enable_error_tracker_logging = $value;
        return $this;
    }


    protected function canPush()
    {
        if (empty(config("devpilot.app_key")) || empty(config("devpilot.base_url"))) {
            return false;
        }
        if (!$this->should_log || !$this->can_log) {
            return false;
        }
        return true;
    }

    protected static function log(string $message, array $data = []): void
    {
        (new BaseTrackerService)->logResponse($message, $data);
    }

    protected function logResponse(string $message, array $data = []): void
    {
        if ($this->enable_error_tracker_logging) {
            $this->logger($message, $data, config("devpilot.enable_error_tracker_logging"));
        }
    }


    public function addBreadcrumb($name, $type = null, array $meta_data = [])
    {
        $type = in_array($type, Breadcrumb::getTypes(), true) ? $type : Breadcrumb::MANUAL_TYPE;
        $this->breadcrub_recorder->add(new Breadcrumb($name, $type, $meta_data));
    }

    protected function clearBreadcrumbs(): void
    {
        $this->breadcrub_recorder->clear();
    }

    /**
     * Get the breadcrumbs of the error.
     *
     * @return \TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Breadcrumbs\Breadcrumb[]
     */
    protected function getBreadcrumbs()
    {
        return $this->breadcrub_recorder->getBreadcrumbs();
    }


    /**
     * Set the error severity.
     *
     * @param string|null $severity the error severity
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setSeverity(string $severity)
    {
        if (in_array($severity, ErrorConstants::SERVERITY_OPTIONS, true)) {
            $this->severity = $severity;
        } else {
            throw new InvalidArgumentException('The severity must be either "error", "warning", or "info".');
        }

        return $this;
    }

    /**
     * Get the error severity.
     *
     * @return string
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * Check if error was handled or not.
     *
     * @return bool
     */
    protected function isUnhandled(): bool
    {
        $processor = new BacktraceProcessor($this->backtrace);
        return $processor->isUnhandled();
    }


    /**
     * Add an extra information for this error.
     *
     * @param string $key The key of the meta_data
     *
     * @param string|array $value The value of the meta_data
     *
     * @return void
     */
    public function addMetaData(string $key, $value)
    {
        $this->meta_data[$key] = $value;
    }

    /**
     * Set the extra information for this error.
     *
     * @param array $data The content of the meta_data
     *
     * @return $this
     */
    public function setMetaData(array $data)
    {
        $this->meta_data = $data;
        return $this;
    }

    /**
     * Remove an extra information for this error.
     *
     * @param string $key The key of the meta_data
     *
     * @return void
     */
    public function removeMetaData($key)
    {
        unset($this->meta_data[$key]);
    }

    /**
     * Clear all extra information for this error.
     *
     * @return void
     */
    public function clearMetaData()
    {
        $this->meta_data = [];
    }

    /**
     * Get all extra information for this error.
     *
     * @return array
     */
    public function getMetaData()
    {
        return $this->meta_data;
    }


    /**
     * Add a tag for this error.
     *
     * @param string $key The key of the tag
     *
     * @param string|null $value The value of the tag
     *
     * @return void
     */
    public function addTag(string $key, string $value = null)
    {
        $this->tags[$key] = $value;
    }

    /**
     * Set tags for this error.
     *
     * @param array $data The content of the tags
     *
     * @return $this
     */
    public function setTags(array $data)
    {
        $this->tags = $data;
        return $this;
    }

    /**
     * Remove a tag for this error.
     *
     * @param string $key The key of the tag
     *
     * @return void
     */
    public function removeTag($key)
    {
        unset($this->tags[$key]);
    }

    /**
     * Clear all tags for this error.
     *
     * @return void
     */
    public function clearTags()
    {
        $this->tags = [];
    }

    /**
     * Get all tags for this error.
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }


    /**
     * Add a custom for this error.
     *
     * @param string $key The key of the custom tab
     *
     * @param string $key The title of the custom tab
     *
     * @param string|null $value The data for the custom tab
     *
     * @return void
     */
    public function addCustomTab(string $key, string $title,  array $data)
    {
        $this->custom_tabs[$key] = [
            "title" => $title,
            "key" => $key,
            "data" => $data
        ];
    }


    /**
     * Remove a custom tab for this error.
     *
     * @param string $key The key of the custom tab
     *
     * @return void
     */
    public function removeCustomTab($key)
    {
        unset($this->custom_tabs[$key]);
    }

    /**
     * Clear all custom tabs for this error.
     *
     * @return void
     */
    public function clearCustomTabs()
    {
        $this->custom_tabs = [];
    }

    /**
     * Get all custom tabs for this error.
     *
     * @return array
     */
    public function getCustomTabs()
    {
        return $this->custom_tabs;
    }
}
