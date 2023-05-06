<?php

namespace TechiesAfrica\Devpilot\Console\Commands\ActivityTracker;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use TechiesAfrica\Devpilot\Services\ActivityTracker\ActivityTrackerService;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;
use TechiesAfrica\Devpilot\Traits\General\ConfigurationTrait;

class StatusCommand extends Command
{
    use LayoutTrait , ConfigurationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:activity_tracker:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the status of the activity tracker service.';

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
        $this->line("Activity tracker status check started....");
        $table->setHeaders([
            "#",
            "FIELD",
            "VALUE",
        ]);

        $table->setRows([
            [1 , "User Access Token Check" , !empty($this->getAuthenticationUserAccessToken()) ? "Passed" : "Failed. Kindly set it in your env."],
            [2 , "App Key Check" , !empty($this->getAuthenticationAppKey()) ? "Passed" : "Failed. Kindly set it in your env."],
            [3 , "App Secret Check" , !empty($this->getAuthenticationAppSecret()) ? "Passed" : "Failed. Kindly set it in your env."],
            [4 , "Logging Enabled Check" , $this->isActivityTrackerEnabled() ? "true" : "false"],
        ]);

        $table->render();
        $this->line("Activity Tracker status check completed....");
        $this->consoleFooter();
    }
}
