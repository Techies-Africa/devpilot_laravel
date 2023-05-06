<?php

namespace TechiesAfrica\Devpilot\Console\Commands\General;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;

class UninstallCommand extends Command
{
    use LayoutTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall Devpilot from an application.';

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

        $this->info('Uninstalling Devpilot from application...');

        if ($this->fileExists(
            $path = config_path("devpilot.php")
        )) {
            if(File::delete($path)){
                $this->line("config/devpilot.php file deleted successfully.");
            }
        }

        if ($this->fileExists(
            $path = app_path("Http/Middleware/Devpilot/ActivityTracker/TrackerMiddleware.php")
        )) {
            if(File::delete($path)){
                $this->line("Http/Middleware/Devpilot/ActivityTracker/TrackerMiddleware.php file deleted successfully.");
            }

        }

        if ($this->fileExists($path = base_path(".devpilot"))) {
            shell_exec("rm -rf '" . $path . "'");
            $this->line(".devpilot folder deleted successfully.");
        }

        $this->info("Devpilot uninstalled sucessfully...");
        $this->consoleFooter();
    }

    private function fileExists($file_path)
    {
        return File::exists($file_path);
    }
}
