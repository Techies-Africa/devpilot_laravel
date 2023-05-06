<?php

namespace TechiesAfrica\Devpilot\Services\ErrorTracker;

use InvalidArgumentException;
use TechiesAfrica\Devpilot\Constants\ErrorConstants;
use TechiesAfrica\Devpilot\Exceptions\ErrorTracker\ErrorTrackerException;
use TechiesAfrica\Devpilot\Services\BaseService;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Breadcrumbs\Breadcrumb;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Breadcrumbs\Recorder;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\BacktraceProcessor;
use TechiesAfrica\Devpilot\Services\ErrorTracker\Core\Request\BasicResolver;
use TechiesAfrica\Devpilot\Traits\General\ErrorTrackerTrait;
use Throwable;

class BaseTrackerService extends BaseService
{
    use ErrorTrackerTrait;
    protected Throwable $exception;
    protected bool $can_log = true;
    protected bool $should_log = true;
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
        $this->setShouldLog($this->isErrorTrackerEnabled());
        $this->setEnableLogging($this->isErrorTrackerLoggingEnabled());
        parent::__construct();
        $this->level = "error";
        $this->breadcrub_recorder = new Recorder;
        $this->resolver = new BasicResolver;
    }

    protected function preRequest()
    {
        $request = request();
        $this->request = $request;
        $this->server = $this->filterServerData($request->server());
        $this->headers = $request->headers->all();
        $this->ip_address = $request->ip();
        return $this;
    }


    // protected function setException(Exception $exception)
    // {
    //     $this->exception = $exception;
    //     return $this;
    // }

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
        if (empty($this->getAuthenticationAppKey()) || empty($this->getGeneralBaseUrl())) {
            return $this->checkIfVerbose(
                new ErrorTrackerException("Devpilot app keys or base url not configured properly."),
                false
            );
        }
        if (!$this->should_log || !$this->can_log) {
            return $this->checkIfVerbose(
                new ErrorTrackerException("Devpilot activity tracking disabled."),
                false
            );
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
            $this->logger($message, $data, $this->getErrorTrackerLogChannel());
        }
    }


    /**
     * Add new  breadcrumb
     *
     * @param string $name the name of the breadcrumb
     * @param string|null $type the type of breadcrumb
     * @param array $metadata extra data for the breadcrumb
     *
     * @return $this
     */
    public function addBreadcrumb(string $name,string $type = null, array $metadata = [])
    {
        $type = in_array($type, Breadcrumb::getTypes(), true) ? $type : Breadcrumb::MANUAL_TYPE;
        $this->breadcrub_recorder->add(new Breadcrumb($name, $type, $metadata));
        return $this;
    }


     /**
     * Remove all saved breadcrumbs for the error
     *
     * @return $this
     */
    protected function clearBreadcrumbs()
    {
        $this->breadcrub_recorder->clear();
        return $this;
    }

    /**
     * Get the breadcrumbs of the error.
     *
     * @return array
     */
    protected function getBreadcrumbs()
    {
        $breadcrumbs = $this->breadcrub_recorder->getBreadcrumbs();
        foreach ($breadcrumbs as $key => $value) {
            $breadcrumbs[$key] = $value->toArray();
        }
        return $breadcrumbs;
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
     * @param string $key The key of the metadata
     *
     * @param string|array $value The value of the metadata
     *
     * @return void
     */
    public function addMetadata(string $key,mixed $value)
    {
        $this->metadata[$key] = $value;
        return $this;
    }

    /**
     * Set the extra information for this error.
     *
     * @param array $data The content of the metadata
     *
     * @return $this
     */
    public function setMetadata(array $data)
    {
        $this->metadata = $data;
        return $this;
    }

    /**
     * Remove an extra information for this error.
     *
     * @param string $key The key of the metadata
     *
     * @return void
     */
    public function removeMetadata(string $key)
    {
        unset($this->metadata[$key]);
        return $this;
    }

    /**
     * Clear all extra information for this error.
     *
     * @return void
     */
    public function clearMetadata()
    {
        $this->metadata = [];
        return $this;
    }

    /**
     * Get all extra information for this error.
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->filterMetadata($this->metadata);
    }


    /**
     * Add a tag for this error.
     *
     * @param string $key The key of the tag
     *
     * @param string|null $value The value of the tag
     *
     * @return $this
     */
    public function addTag(string $key, string $value = null)
    {
        $this->tags[$key] = $value;
        return $this;
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
     * @return $this
     */
    public function removeTag(string $key)
    {
        unset($this->tags[$key]);
        return $this;
    }

    /**
     * Clear all tags for this error.
     *
     * @return $this
     */
    public function clearTags()
    {
        $this->tags = [];
        return $this;
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
     * @param array|null $value The data for the custom tab
     *
     * @return void
     */
    public function addCustomTab(string $key, string $title,  array $data = null)
    {
        $this->custom_tabs[$key] = [
            "title" => $title,
            "key" => $key,
            "data" => $data
        ];
        return $this;
    }


    /**
     * Remove a custom tab for this error.
     *
     * @param string $key The key of the custom tab
     *
     * @return $this
     */
    public function removeCustomTab(string $key)
    {
        unset($this->custom_tabs[$key]);
        return $this;
    }

    /**
     * Clear all custom tabs for this error.
     *
     * @return $this
     */
    public function clearCustomTabs()
    {
        $this->custom_tabs = [];
        return $this;
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
