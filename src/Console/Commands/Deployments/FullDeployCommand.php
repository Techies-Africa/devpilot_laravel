<?php

namespace TechiesAfrica\Devpilot\Console\Commands\Deployments;

use Illuminate\Console\Command;
use TechiesAfrica\Devpilot\Exceptions\Deployments\DeploymentException;
use TechiesAfrica\Devpilot\Exceptions\General\GuzzleException;
use TechiesAfrica\Devpilot\Services\Deployments\DeploymentService;
use Throwable;

class FullDeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:deploy {--b=|branch=} {--h=|hooks=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy an application with full customizable options';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $options_array = [];
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info("Initializing deployment");
            $this->withOptions();
            $this->validateOptions();
            $service = new DeploymentService();
            dd($service->deploy($this->options_array));
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        } catch (DeploymentException $e) {
            $this->warn($e->getMessage());
        } catch (Throwable $e) {
            throw $e;
        }
    }

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
}
