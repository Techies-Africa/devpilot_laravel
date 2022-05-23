<?php

namespace TechiesAfrica\Devpilot\Console\Commands\ActivityTracker;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use TechiesAfrica\Devpilot\Exceptions\General\GuzzleException;
use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Exceptions\General\ValidationException;
use TechiesAfrica\Devpilot\Traits\Commands\ActivityTrackerTrait;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;
use Throwable;

class TestCommand extends Command
{
    use LayoutTrait, ActivityTrackerTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:tracker:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the activity tracker service';

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
        try {

            $this->consoleHeader();
            $this->test();
            $table = new Table($this->output);
            $this->line("Activity Tracker test started....");
            $table->setHeaders([
                "#",
                "FIELD",
                "VALUE",
            ]);

            $table->setRows([
                [1, "User Access Token Check", !empty(config("devpilot.user_access_token")) ? "Passed" : "Failed. Kindly set it in your env."],
                [2, "App Key Check", !empty(config("devpilot.app_key")) ? "Passed" : "Failed. Kindly set it in your env."],
                [3, "App Secret Check", !empty(config("devpilot.app_secret")) ? "Passed" : "Failed. Kindly set it in your env."],
                [4, "Logging Enabled Check", config("devpilot.enable_activity_tracking") ? "true" : "false"],
                [4, "Test Logging", "Test data sent. Login in to your devepilot dashaboard to view details"],
            ]);

            $table->render();
            $this->line("Activity Tracker test completed....");
        } catch (ValidationException $e) {
            $this->displayValidatorErrors($e->errors);
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        } catch (ServerErrorException $e) {
            $this->warn($e->getMessage());
        } catch (Throwable $e) {
            throw $e;
        }
        $this->consoleFooter();
    }
}
