<?php

namespace TechiesAfrica\Devpilot\Console\Commands\ActivityTracker;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use TechiesAfrica\Devpilot\Exceptions\ActivityTracker\ActivityTrackerException;
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
            $this->line('<fg=blue;>Activity tracker test started.... <fg=white;bg=black></>');
            $this->test();
            $this->line('<fg=blue;>Activity tracker test completed.... <fg=white;bg=black></>');
        } catch (ValidationException $e) {
            $this->displayValidatorErrors($e->errors);
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        } catch (ServerErrorException $e) {
            $this->warn($e->getMessage());
        } catch (ActivityTrackerException $e) {
            $this->warn($e->getMessage());
        } catch (Throwable $e) {
            throw $e;
        }
        $this->consoleFooter();
    }
}
