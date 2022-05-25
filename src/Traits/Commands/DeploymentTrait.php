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
            "commands" => null,
            "storage_paths" => null,
        ];
    }

    public function loadConfig()
    {
        $config_path = $this->config_path;
        if (!file_exists($config_path)) {
            return;
        }
        $config_content = file_get_contents($config_path);
        if (empty($config_content)) {
            return;
        }
        $config = json_decode($config_content, true);
        if (empty($config)) {
            return;
        }
        $this->options_array["hooks"] = $this->options_array["hooks"] ?? $config["hooks"]["status"] ?? null;
        $this->options_array["commands"] = $config["hooks"]["commands"] ?? null;
        $this->options_array["storage_paths"] = $config["storage"]["paths"] ?? null;
    }

    public function validateOptions()
    {
        if (!in_array($this->options_array["hooks"], [null, "active", "inactive", "all"])) {
            throw new DeploymentException("Invalid hooks value provided. Allowed values are: active, inactive and all.");
        }

        $config_path = $this->config_path;
        $commands = [];
        $paths = [];
        if (!empty($commands_ = $this->options_array["commands"])) {
            foreach ($commands_ as $command) {

                if (!$command["execute"] ?? false) {
                    continue;
                }
                unset($command["execute"]);

                foreach ($fields = ["name", "command", "type"] as $field) {
                    if (empty($command[$field] ?? null)) {
                        throw new DeploymentException("The field <fg=red>$field</> in $config_path under the commands options is required.
                        \n<fg=green>Required fields for commands are: " . implode(", ", $fields) . ".</>");
                    }
                }

                if (!in_array($t = $command["type"], ["update", "delete"])) {
                    throw new DeploymentException("The type <fg=red>$t</> found in $config_path under the commands options is invalid.
                    \n<fg=green>Options are: pre_release, post_release.</>");
                }

                $commands[] = $command;
            }
        }

        if (!empty($paths_ = $this->options_array["storage_paths"])) {
            foreach ($paths_ as $path) {

                if (!$path["execute"] ?? false) {
                    continue;
                }
                unset($path["execute"]);

                foreach ($fields = ["path", "action"] as $field) {
                    if (empty($path[$field] ?? null)) {
                        throw new DeploymentException("The field <fg=red>$field</> in $config_path under the storage options is required.
                        \n<fg=green>Required fields for commands are: " . implode(", ", $fields) . ".</>");
                    }
                }

                if (!in_array($a = $path["action"], ["update", "delete"])) {
                    throw new DeploymentException("The action <fg=red>$a</> found in $config_path under the storage options is invalid.
                    \n<fg=green>Options are: update, delete.</>");
                }
                if (!file_exists($p = $path["path"])) {
                    throw new DeploymentException("The path <fg=red>$p</> found in $config_path under the storage options does not exist.");
                }
                $paths[] = $path;
            }
        }

        $this->options_array["commands"] = empty($commands) ? null : $commands;
        $this->options_array["storage_paths"] =  empty($paths) ? null : $paths;
        dd($this->options_array);
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
        if (!config("devpilot.enable_deployment", false)) {
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
