<?php

namespace TechiesAfrica\Devpilot\Console\Commands\Env;

use Illuminate\Console\Command;
use TechiesAfrica\Devpilot\Exceptions\General\GuzzleException;
use TechiesAfrica\Devpilot\Exceptions\General\ServerErrorException;
use TechiesAfrica\Devpilot\Exceptions\General\ValidationException;
use TechiesAfrica\Devpilot\Services\Env\EnvService;
use TechiesAfrica\Devpilot\Traits\Commands\EnvTrait;
use TechiesAfrica\Devpilot\Traits\Commands\LayoutTrait;
use Throwable;

class SaveCommand extends Command
{
    use LayoutTrait, EnvTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devpilot:env:save {--f|filename=.env}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save the .env content to a remote application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public EnvService $service;
    public function __construct()
    {
        parent::__construct();
        $this->service = new EnvService();
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
            $filename = $this->option("filename");
            $this->line("Connection to remote application...");
            $this->save($filename);
            $this->line("Env values saved to remote application successfully ....");
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
