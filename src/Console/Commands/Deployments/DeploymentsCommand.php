<?php

namespace TechiesAfrica\Devpilot\Console\Commands\Deployments;

use Illuminate\Console\Command;
use TechiesAfrica\Devpilot\Services\Deployments\DeploymentService;

class DeploymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy an application';

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
        $service = new DeploymentService();
        dd($service->deploy());
    }
}
