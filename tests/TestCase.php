<?php

namespace TechiesAfrica\Devpilot\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;
use TechiesAfrica\Devpilot\Providers\DevpilotServiceProvider;

class TestCase extends TestbenchTestCase
{
  public function setUp(): void
  {
    parent::setUp();
    // additional setup
  }

  protected function getPackageProviders($app)
  {
    return [
      DevpilotServiceProvider::class,
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
    // perform environment setup
  }
}
