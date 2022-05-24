<?php

namespace TechiesAfrica\Devpilot\Console\Commands\Env;

use Illuminate\Console\Command;
use TechiesAfrica\Devpilot\Exceptions\General\GuzzleException;
use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Exceptions\General\ValidationException;
use TechiesAfrica\Devpilot\Services\Env\EnvService;
use TechiesAfrica\Devpilot\Traits\Commands\ConfigTrait;
use TechiesAfrica\Devpilot\Traits\Commands\EnvTrait;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;
use Throwable;

class LoadCommand extends Command
{
    use LayoutTrait, EnvTrait, ConfigTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:env:load {--f|filename=.env}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load the .env content from a remote application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public EnvService $service;
    public $env_path;
    public function __construct()
    {
        parent::__construct();
        $this->service = new EnvService();
        $this->env_path = base_path(".devpilot/.env");
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
            $this->hasWorkingDirectory();
            $filename = $this->option("filename");
            $this->line("Connection to remote application...");
            $this->load($filename);
            $this->line("Env values loaded successfully...");
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
