<?php

namespace TechiesAfrica\Devpilot\Console\Commands\General;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;

class InstallCommand extends Command
{
    use LayoutTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup devpilot on an application';

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

        $this->info('Setting up application package...');

        if (!$this->fileExists(config_path("devpilot.php"))) {
            $this->publishFile("config", false);
        } else {
            if ($this->shouldOverwriteFile('Config file already exists. Do you want to overwrite it?')) {
                $this->info('Overwriting configuration file...');
                $this->publishFile("config", true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        if (!$this->fileExists(app_path("Http/Middleware/ActivityTracker/TrackerMiddleware.php"))) {
            $this->publishFile("middleware", false);
        } else {
            if ($this->shouldOverwriteFile('Middleware file already exists. Do you want to overwrite it?')) {
                $this->info('Overwriting middleware file...');
                $this->publishFile("middleware", true);
            } else {
                $this->info('Existing middleware was not overwritten');
            }
        }

        $this->info('Installed Devpilot sucessfully...');
        $this->consoleFooter();
    }

    private function fileExists($file_path)
    {
        return File::exists($file_path);
    }

    private function shouldOverwriteFile($message)
    {
        return $this->confirm(
            $message,
            false
        );
    }

    private function publishFile($tag, $forcePublish = false)
    {
        $params = [
            '--provider' => "TechiesAfrica\Devpilot\Providers\DevpilotServiceProvider",
            '--tag' => $tag
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
