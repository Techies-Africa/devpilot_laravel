<?php

namespace TechiesAfrica\Devpilot\Traits\General;


trait ConfigurationTrait
{
    /**
     * Get the base url endpoint for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getGeneralBaseUrl()
    {
        return config("devpilot.general.base_url");
    }


    /**
     * Get the hostname from the config file.
     *
     * @return string|null
     */
    public function getGeneralHostname()
    {
        return config("devpilot.general.hostname");
    }












    /**
     * Get the user access token for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getAuthenticationUserAccessToken()
    {
        return config("devpilot.authentication.user_access_token");
    }

    /**
     * Get the user access token passphrase for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getAuthenticationUserAccessTokenPassphrase()
    {
        return config("devpilot.authentication.user_access_token_passphrase");
    }

    /**
     * Get the app key for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getAuthenticationAppKey()
    {
        return config("devpilot.authentication.app_key");
    }

    /**
     * Get the app secret for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getAuthenticationAppSecret()
    {
        return config("devpilot.authentication.app_secret");
    }













    /**
     * Check if deployment is enabled for Devpilot from the config file.
     *
     * @return bool|null
     */
    public function isDeploymentEnabled($default = true)
    {
        return config("devpilot.deployment.enable", $default);
    }

    /**
     * Get the default branch for deployment for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getDeploymentDefaultBranch()
    {
        return config("devpilot.deployment.default_branch");
    }

    /**
     * Check if deployment logging is enabled for Devpilot from the config file.
     *
     * @return bool|null
     */
    public function isDeploymentLoggingEnabled()
    {
        return config("devpilot.deployment.enable_logging", false);
    }

    /**
     * Get the deployment log channel for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getDeploymentLogChannel()
    {
        return config("devpilot.deployment.log_channel");
    }

    /**
     * Get the callback url for deployment for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getDeploymentCallbackUrl()
    {
        return config("devpilot.deployment.callback_url");
    }











    /**
     * Check if activity tracker is enabled for Devpilot from the config file.
     *
     * @return bool|null
     */
    public function isActivityTrackerEnabled()
    {
        return config("devpilot.activity_tracker.enable", true);
    }

    /**
     * Check if activity tracker logging is enabled for Devpilot from the config file.
     *
     * @return bool|null
     */
    public function isActivityTrackerLoggingEnabled()
    {
        return config("devpilot.activity_tracker.enable_logging", false);
    }

    /**
     * Get the activity tracker log channel for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getActivityTrackerLogChannel()
    {
        return config("devpilot.activity_tracker.log_channel");
    }

    /**
     * Get the callback url for activity tracker for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getActivityTrackerCallbackUrl()
    {
        return config("devpilot.activity_tracker.callback_url");
    }

    /**
     * Get the ignored routes for activity tracker for Devpilot from the config file.
     *
     * @return array|null
     */
    public function getActivityTrackerIgnoreRoutes()
    {
        return config("devpilot.activity_tracker.ignore_routes");
    }

    /**
     * Get the ignored middlewares for activity tracker for Devpilot from the config file.
     *
     * @return array|null
     */
    public function getActivityTrackerIgnoreMiddlewares()
    {
        return config("devpilot.activity_tracker.ignore_middlewares");
    }

    /**
     * Get the authenticated middlewares for activity tracker for Devpilot from the config file.
     *
     * @return array|null
     */
    public function getActivityTrackerAuthenticatedMiddlewares()
    {
        return config("devpilot.activity_tracker.authenticated_middlewares");
    }

    /**
     * Get the user fields for activity tracker for Devpilot from the config file.
     *
     * @return array|null
     */
    public function getActivityTrackerUserFields()
    {
        return config("devpilot.activity_tracker.user_fields");
    }














    /**
     * Check if error tracker is enabled for Devpilot from the config file.
     *
     * @return bool|null
     */
    public function isErrorTrackerEnabled()
    {
        return config("devpilot.error_tracker.enable", true);
    }

    /**
     * Get the project path from the config file.
     *
     * @return string|null
     */
    public function getErrorTrackerProjectPath()
    {
        return config("devpilot.error_tracker.project_path");
    }

    /**
     * Get the path to strip from stacktrace from the config file.
     *
     * @return string|null
     */
    public function getErrorTrackerStripPath()
    {
        return config("devpilot.error_tracker.strip_path");
    }


    /**
     * Get the keys to remove from metadata payload in stacktrace from the config file.
     *
     * @return string|null
     */
    public function getErrorTrackerMetadataFilters()
    {
        return config("devpilot.error_tracker.metadata_filters");
    }

    /**
     * Check if should sen code snippets in stacktrace from the config file.
     *
     * @return string|null
     */
    public function shouldErrorTrackerSendCode()
    {
        return config("devpilot.error_tracker.send_code");
    }


    /**
     * Check if error tracker logging is enabled for Devpilot from the config file.
     *
     * @return bool|null
     */
    public function isErrorTrackerLoggingEnabled()
    {
        return config("devpilot.error_tracker.enable_logging", false);
    }

    /**
     * Get the error tracker log channel for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getErrorTrackerLogChannel()
    {
        return config("devpilot.error_tracker.log_channel");
    }

    /**
     * Get the callback url for error tracker for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getErrorTrackerCallbackUrl()
    {
        return config("devpilot.error_tracker.callback_url");
    }












    /**
     * Get the callback url for error tracker for Devpilot from the config file.
     *
     * @return string|null
     */
    public function getCommandFilterDisabled()
    {
        return config("devpilot.command_filter.disabled");
    }
}
