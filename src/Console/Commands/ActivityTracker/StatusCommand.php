<?php

namespace TechiesAfrica\Devpilot\Console\Commands\ActivityTracker;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use TechiesAfrica\Devpilot\Services\ActivityTracker\TrackerService;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;

class StatusCommand extends Command
{
    use LayoutTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:tracker:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the status of the activity tracker service';

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
        $this->consoleHeader();
        $table = new Table($this->output);
        $this->line("Activity Tracker status check started....");
        $table->setHeaders([
            "#",
            "FIELD",
            "VALUE",
        ]);

        $table->setRows([
            [1 , "User Access Token Check" , !empty(config("devpilot.user_access_token")) ? "Passed" : "Failed. Kindly set it in your env."],
            [2 , "App Key Check" , !empty(config("devpilot.app_key")) ? "Passed" : "Failed. Kindly set it in your env."],
            [3 , "App Secret Check" , !empty(config("devpilot.app_secret")) ? "Passed" : "Failed. Kindly set it in your env."],
            [4 , "Logging Enabled Check" , config("devpilot.enable_activity_tracking") ? "true" : "false"],
        ]);

        $table->render();
        $this->line("Activity Tracker status check completed....");
        $this->consoleFooter();
    }
}
