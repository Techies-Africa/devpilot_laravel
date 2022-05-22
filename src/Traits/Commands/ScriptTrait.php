<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use TechiesAfrica\Devpilot\Traits\Commands\Errors\ErrorHandlerTrait;

trait ScriptTrait
{
    use ErrorHandlerTrait;

    public function executeAppCommands($commands)
    {
        $process  = $this->service->execute($commands);
        if (!in_array($process["status"], [200, 201])) {
            $this->handleErrors($process);
        }
        return $process["data"]["data"];
    }


    public function displayResponse($scripts)
    {
        $table = new Table($this->output);
        $table->setHeaders([
            "#",
            "FIELD",
            "VALUE",
        ]);

        $i = 1;
         foreach ($scripts as $script) {
            $i = 1;
            $table->addRow([new TableCell('------- Script Information Start --------', ['colspan' => 2])]);
            $table->addRow(new TableSeparator(['colspan' => 2]));
            foreach ($script as $key => $value) {
                $table->addRow([
                    $i,
                    ucwords(str_replace("_", " ", $key)),
                    $value
                ]);
                $i++;
            }
            $table->addRow(new TableSeparator(['colspan' => 2]));
            $table->addRow([new TableCell('------- Script Information End --------', ['colspan' => 2])]);
            $table->addRow(new TableSeparator(['colspan' => 2]));
        }

        $table->render();
        $this->consoleFooter();
    }
}
