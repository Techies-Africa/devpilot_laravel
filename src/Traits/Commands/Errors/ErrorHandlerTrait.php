<?php

namespace TechiesAfrica\Devpilot\Traits\Commands\Errors;

use Symfony\Component\Console\Helper\Table;
use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Exceptions\General\ValidationException;

trait ErrorHandlerTrait
{
    public function displayValidatorErrors($errors)
    {
        $this->line("------------------------------");
        $this->line('<fg=red;bg=black>       Validation Errors</>');

        $table = new Table($this->output);
        $table->setHeaders([
            "#",
            "FIELD",
            "MESSAGE",
        ]);
        $i = 1;
        foreach ($errors as $key => $error) {
            foreach ($error as $value) {
                $table->addRow([
                    $i,
                    ucwords(str_replace("_", " ", $key)),
                    $value
                ]);
                $i++;
            }
        }

        $table->render();
        $this->line("------------------------------");
    }

    public function handleErrors($data)
    {
        if ($data["status"] == 422) {
            $errors = json_decode($data["message"], true)["errors"];
            throw new ValidationException($data["message"], $errors, $data["status"]);
        } elseif ($data["status"] == 400) {
            $message = json_decode($data["message"], true)["message"];
            throw new ServerErrorException($message);
        } else {
            // dd($data);
            throw new ServerErrorException("An error occured on the server. Don`t worry , its not your fault.");
        }
    }
}
