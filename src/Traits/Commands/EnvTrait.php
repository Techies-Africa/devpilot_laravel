<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Traits\Commands\Errors\ErrorHandlerTrait;

trait EnvTrait
{
    use ErrorHandlerTrait;


    public function load($filename = null)
    {

        $process  = $this->service->load($filename);
        if (!in_array($process["status"], [200, 201])) {
            $this->handleErrors($process);
        }

        $content = $process["data"]["data"];
        $raw_content = base64_decode($content);
        $env_path = $this->env_path;
        file_put_contents($env_path , $raw_content);
        $this->info("Created $env_path file successfully.");
    }

    public function save($filename = null)
    {
        $env_path = $this->env_path;
        $raw_content = file_get_contents($env_path);
        $content = base64_encode($raw_content);

        $process  = $this->service->save($content , $filename);
        if (!in_array($process["status"], [200, 201])) {
            $this->handleErrors($process);
        }
        try {
            if(file_exists($env_path)){
                unlink($env_path);
                $this->info("Deleted $env_path file successfully.");
            }
        } catch (\Throwable $th) {
            $this->warn("Unable to delete $env_path file. Please try to delete it manually.");
        }
    }
}
