<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

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
        file_put_contents(base_path(".env.devpilot") , $raw_content);
        $this->info("Created .env.devpilot file successfully");
    }

    public function save($filename = null)
    {
        $file_path = base_path(".env.devpilot");
        $raw_content = file_get_contents($file_path);
        $content = base64_encode($raw_content);

        $process  = $this->service->save($content , $filename);
        if (!in_array($process["status"], [200, 201])) {
            $this->handleErrors($process);
        }
        try {
            if(file_exists($file_path)){
                unlink($file_path);
                $this->info("Deleted .env.devpilot file successfully");
            }
        } catch (\Throwable $th) {
            $this->warn("Unable to delete .env.devpilot file. Please try to delete it manually.");
        }
    }
}
