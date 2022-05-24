<?php

namespace TechiesAfrica\Devpilot\Console\Commands\Deployments;

use Illuminate\Console\Command;
use TechiesAfrica\Devpilot\Exceptions\Deployments\DeploymentException;
use TechiesAfrica\Devpilot\Exceptions\General\GuzzleException;
use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Exceptions\General\ValidationException;
use TechiesAfrica\Devpilot\Services\Deployments\DeploymentService;
use TechiesAfrica\Devpilot\Traits\Commands\ConfigTrait;
use TechiesAfrica\Devpilot\Traits\Commands\DeploymentTrait;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;
use Throwable;

class FullDeployCommand extends Command
{
    use DeploymentTrait, LayoutTrait, ConfigTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:deploy {--b=|branch=} {--h=|hooks=} {--refresh_interval=10} {--s|silent=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy an application with full customizable options';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $options_array = [];
    public $config_path;
    public DeploymentService $service;
    public function __construct()
    {
        parent::__construct();
        $this->service = new DeploymentService();
        $this->config_path = base_path(".devpilot/deployment/deployment.json");
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
            $this->info("Initializing deployment...");
            $this->withOptions();
            $this->loadConfig();
            $this->validateOptions();

            $deployment = $this->deploy();
            $this->showDeployment($deployment);

            if ($this->option("silent") ?? false) {
                $show_url = $deployment["show_url"];
                $this->info("Deployment running in the background...");
                $this->info("To view deployment progress , here`s the link: $show_url");
            } else {
                $this->info("Listening to progress...");
                $this->listenToUpdates($deployment["id"], $this->option("refresh_interval") ?? 10);
            }
        } catch (ValidationException $e) {
            $this->displayValidatorErrors($e->errors);
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        } catch (DeploymentException $e) {
            $this->warn($e->getMessage());
        } catch (ServerErrorException $e) {
            $this->warn($e->getMessage());
        } catch (Throwable $e) {
            throw $e;
        }
        $this->consoleFooter();
    }
}
