<?php

namespace TechiesAfrica\Devpilot\Traits\General;

use Symfony\Component\Console\Helper\Table;

trait ValidationTrait
{
    public function displayErrors($data)
    {
        $this->line("------------------------------");
        $this->line('<fg=red;bg=black>       Validation Errors</>');
        $errors = json_decode($data["message"]);

        $table = new Table($this->output);
        $table->setHeaders([
            "#",
            "FIELD",
            "MESSAGE",
        ]);
        $i = 1;
        foreach ($errors as $key => $value) {
            $table->addRow([
                $i,
                ucwords(str_replace("_", " ", $key)),
                $value
            ]);
            $i++;
        }

        $table->render();
        $this->line("------------------------------");
    }
}
