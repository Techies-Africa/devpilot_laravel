<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use TechiesAfrica\Devpilot\Exceptions\Deployments\DeploymentException;
use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Traits\Commands\Errors\ErrorHandlerTrait;

trait DeploymentTrait
{
    use ErrorHandlerTrait;

    public function withOptions()
    {
        $this->options_array = [
            "branch" => $this->option("branch"),
            "hooks" => $this->option("hooks"),
        ];
    }

    public function validateOptions()
    {
        if (!in_array($this->options_array["hooks"], [null, "active", "inactive", "all"])) {
            throw new DeploymentException("Invalid hooks value provided. Allowed values are: active, inactive and all.");
        }
    }

    public function listDeployments(array $deployments)
    {
        $table = new Table($this->output);
        // Set the table headers.
        $table->setHeaders([
            "#",
            "ID",
            "Provider",
            "Branch",
            "Progress",
            "Status",
            //    "Repository",
            //    "Owner",
            //    "Committer",
            //    "Commit Message",
            //    "Commit Url",
            "Started At",
            "Ended At",
            "Duration (Ms)",
        ]);

        // Set the contents of the table.
        foreach ($deployments as $key => $deployment) {
            $table->addRow([
                $key + 1,
                $deployment["id"],
                $deployment["provider"],
                $deployment["branch"],
                $deployment["progress"],
                $deployment["status"],
                //    $deployment["repository"],
                //    $deployment["owner"],
                //    $deployment["committer"],
                //    $deployment["message"],
                //    $deployment["commit_url"],
                $deployment["started_at"],
                $deployment["ended_at"],
                $deployment["duration"],
            ]);
        }

        $table->render();
    }

    public function showDeployment(array $deployment)
    {
        $table = new Table($this->output);
        // Set the table headers.
        $table->setHeaders([
            "#",
            "FIELD",
            "VALUE",
        ]);

        $i = 1;
        foreach ($deployment as $key => $value) {
            $table->addRow([
                $i,
                ucwords(str_replace("_", " ", $key)),
                $value
            ]);
            $i++;
        }
        $table->render();
    }

    public function deploy(): array
    {
        if (!config("devpilot.enable_deployment" , false)) {
            throw new ServerErrorException("Deployment is disabled from your configurations.");
        }

        $process  = $this->service->deploy($this->options_array);
        if (!in_array($process["status"], [200, 201])) {
            $this->handleErrors($process);
        }

        return $process["data"]["data"];
    }

    public function fetchDeploymentInformation($deployment_id)
    {
        $process = $this->service->information($deployment_id);
        if (!in_array($process["status"], [200, 201])) {
            $this->handleErrors($process);
        }
        return $process["data"]["data"];
    }

    public function listenToUpdates($deployment_id, $refresh_interval = 10)
    {
        $info = $this->fetchDeploymentInformation($deployment_id);
        $deployment = $info["deployment"];
        $progress = $deployment["progress"];

        if (in_array(strtolower($deployment["status"]), ["pending", "processing"])) {
            $this->info("Deployment progress: $progress%...");

            sleep($refresh_interval);
            return $this->listenToUpdates($deployment_id, $refresh_interval);
        }

        $hooks = $info["hooks"];

        $table = new Table($this->output);
        $table->setHeaders([
            "#",
            "FIELD",
            "VALUE",
        ]);

        foreach ($hooks as $hook) {
            $i = 1;
            $table->addRow([new TableCell('------- Hook Information Start --------', ['colspan' => 2])]);
            $table->addRow(new TableSeparator(['colspan' => 2]));
            foreach ($hook as $key => $value) {
                $table->addRow([
                    $i,
                    ucwords(str_replace("_", " ", $key)),
                    $value
                ]);
                $i++;
            }
            $table->addRow(new TableSeparator(['colspan' => 2]));
            $table->addRow([new TableCell('------- Hook Information End --------', ['colspan' => 2])]);
            $table->addRow(new TableSeparator(['colspan' => 2]));
        }
        $table->render();
    }
}
