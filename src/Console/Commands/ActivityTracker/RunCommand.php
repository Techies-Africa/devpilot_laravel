<?php

namespace TechiesAfrica\Devpilot\Console\Commands\Deployments;

use Illuminate\Console\Command;
use TechiesAfrica\Devpilot\Services\ActivityTracker\TrackerService;

class RunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activitytracker:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        dd(TrackerService::isAjax());
    }
}
