<?php

namespace TechiesAfrica\Devpilot\Services\General\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Events\CommandStarting;

class CommandFilterService extends Command
{

    public function handle(CommandStarting $event)
    {
        $executed_command = $event->command;
        $disabled_commands = config("devpilot.disabled_commands");

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
