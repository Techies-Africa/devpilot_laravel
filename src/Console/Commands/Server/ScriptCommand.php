<?php

namespace TechiesAfrica\Devpilot\Console\Commands\Server;

use Illuminate\Console\Command;
use TechiesAfrica\Devpilot\Exceptions\Deployments\DeploymentException;
use TechiesAfrica\Devpilot\Exceptions\General\GuzzleException;
use TechiesAfrica\Devpilot\Exceptions\General\ValidationException;
use TechiesAfrica\Devpilot\Services\Server\ServerService;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;
use TechiesAfrica\Devpilot\Traits\Commands\ScriptTrait;
use Throwable;

class ScriptCommand extends Command
{
    use LayoutTrait, ScriptTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:script {--command=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run commands on an application`s remote server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public ServerService $service;
    public function __construct()
    {
        parent::__construct();
        $this->service = new ServerService();
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
            $commands = $this->option("command");
            if (count($commands) > 0) {

                $this->line("Initializing script ececution...");
                $scripts = $this->executeAppCommands($commands);
                $this->displayResponse($scripts);
                $this->line("Script execution completed....");
            } else {
                $this->warn("No commands were passed to be executed....");
            }
        } catch (ValidationException $e) {
            $this->displayValidatorErrors($e->errors);
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        } catch (DeploymentException $e) {
            $this->warn($e->getMessage());
        } catch (Throwable $e) {
            throw $e;
        }
        $this->consoleFooter();
    }
}
