<?php

namespace TechiesAfrica\Devpilot\Tests\Feature\Installation;

use TechiesAfrica\Devpilot\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallTest extends TestCase
{

    function test_install_command()
    {
        $config_file = config_path('devpilot.php');
        $config_folder = base_path('.devpilot');
        $middleware_path = app_path('Http/Middleware/Devpilot/ActivityTracker/TrackerMiddleware.php');


        Artisan::call("devpilot:uninstall");

        $this->assertFalse(File::exists($config_file));
        $this->assertFalse(File::exists($config_folder));
        $this->assertFalse(File::exists($middleware_path));

        Artisan::call("devpilot:install");

        $this->assertTrue(File::exists($config_file));
        $this->assertTrue(File::exists($config_folder));
        $this->assertTrue(File::exists($middleware_path));
    }


    function test_reinstall_command()
    {
        $config_file = config_path('devpilot.php');

        $this->assertTrue(File::exists($config_file));

        $command = $this->artisan('devpilot:install');
        $command->expectsQuestion("Config file already exists. Do you want to overwrite it?", "yes");
        $command->expectsQuestion("Middleware file already exists. Do you want to overwrite it?", "yes");
        $command->expectsQuestion(".devpilot folder already exists. Do you want to overwrite it?", "yes");
        $command->execute();
        $command->expectsOutput('Overwriting configuration file...');

        $this->assertTrue(File::exists($config_file));

        $this->assertEquals(
            File::get($config_file),
            File::get(__DIR__.'/../../../src/config/devpilot.php')
        );
    }

    function test_uninstall_command()
    {
        $config_file = config_path('devpilot.php');
        $config_folder = base_path('.devpilot');
        $middleware_path = app_path('Http/Middleware/Devpilot/ActivityTracker/TrackerMiddleware.php');

        Artisan::call("devpilot:uninstall");

        $this->assertFalse(File::exists($config_file));
        $this->assertFalse(File::exists($config_folder));
        $this->assertFalse(File::exists($middleware_path));
    }
}
