<?php

namespace TechiesAfrica\Devpilot\Services\General\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Events\CommandStarting;
use TechiesAfrica\Devpilot\Traits\General\ConfigurationTrait;

class CommandFilterService extends Command
{
    use ConfigurationTrait;
    public function handle(CommandStarting $event)
    {
        $executed_command = $event->command;
        $disabled_commands = $this->getCommandFilterDisabled();

        if (!is_array($disabled_commands)) {
            $disabled_commands = explode(",", $disabled_commands);
        }

        if (in_array($executed_command, $disabled_commands)) {
            $this->output = $event->output;
            $this->warn("The command \"$executed_command\" is currently disabled.");
            die();
        }
    }
}
