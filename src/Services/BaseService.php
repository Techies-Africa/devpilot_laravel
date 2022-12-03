<?php

namespace TechiesAfrica\Devpilot\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TechiesAfrica\Devpilot\Constants\GeneralConstants;
use TechiesAfrica\Devpilot\Services\General\Guzzle\GuzzleService;

class BaseService
{
    public GuzzleService $guzzle;
    protected Request $request;
    protected array $server = [];
    protected array $headers = [];
    protected array $meta_data = [];
    protected string $ip_address = "";
    protected array $user = [];
    public array $user_fields = ["id" => "id", "name" => "name", "email" => "email"];
    protected bool $verbose = false;

    public function __construct()
    {
        $this->user_access_token = config("devpilot.user_access_token");
        $this->passphrase = config("devpilot.user_access_token_passphrase");
        $this->app_key = config("devpilot.app_key");
        $this->app_secret = config("devpilot.app_secret");

        $this->guzzle = new GuzzleService([
            'Api-Version' => 'v1',
            "User-Access-Token" => $this->user_access_token,
            "User-Access-Passphrase" => $this->passphrase,
        ]);

    }

    protected function logger(string $message, array $data = [], $channel = "stack"): void
    {
        Log::channel($channel)->info($message, $data);
    }


    protected function mapUserData($user)
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

    function verbose(bool $value = false)
    {
        $this->verbose = $value;
        return $this;
    }


    protected function setMetaData(array $data)
    {
        $this->meta_data = $data;
        return $this;
    }

    protected function filterServerData(array $server)
    {
        $values = [];
        foreach (GeneralConstants::ALLOWED_SERVER_KEYS as $allowed_key) {
            $values[$allowed_key] = $server[$allowed_key] ?? "";
        }
        return $values;
    }


    private function encryptOrDecrypt($action, $string)
    {
        try {
            $output = false;
            $encrypt_method = "AES-256-CBC";
            $secret_key = $this->app_key;
            $secret_iv = $this->app_secret;
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);
            if ($action == 'encrypt') {
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                $output = base64_encode($output);
            } elseif ($action == 'decrypt') {
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            }

            return $output;
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function encrypt(string $value)
    {
        return $this->encryptOrDecrypt("encrypt", $value);
    }

    protected function decrypt(string $value)
    {
        return $this->encryptOrDecrypt("decrypt", $value);
    }

    protected function getEventUUID()
    {
        return $this->encrypt($this->app_key . "|" . time());
    }

    /**
     * Get the hostname of the computer.
     *
     * @return string|null
     */
    protected function getHostname()
    {
        $disabled = explode(',', ini_get('disable_functions'));

        if (function_exists('php_uname') && !in_array('php_uname', $disabled, true)) {
            return php_uname('n');
        }

        if (function_exists('gethostname') && !in_array('gethostname', $disabled, true)) {
            return gethostname();
        }

        return null;
    }


    protected function checkIfVerbose(Exception $exception , $return_value = null)
    {
        if($this->verbose){
            throw $exception;
        }
        return $return_value;
    }

}
