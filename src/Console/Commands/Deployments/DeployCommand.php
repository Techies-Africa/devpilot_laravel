<?php

namespace TechiesAfrica\Devpilot\Console\Commands\Deployments;

use Illuminate\Console\Command;
use TechiesAfrica\Devpilot\Services\Deployments\DeploymentService;
use TechiesAfrica\Devpilot\Traits\General\ConfigurationTrait;

class DeployCommand extends Command
{
    use ConfigurationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy {--silent=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy an application quickly using Devpilot.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $this->deploy();
    }

    private function deploy()
    {
        $params = [
            '--silent' => $this->option("silent"),
            '--branch' => $this->getDeploymentDefaultBranch(),
            '--hooks' => "active",
        ];
        $this->call('devpilot:deploy', $params);
    }
}
