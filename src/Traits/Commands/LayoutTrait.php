<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

trait LayoutTrait
{
    public function consoleHeader()
    {
        $this->line("------------------------------");
        $this->line('<fg=white;bg=black>Starting<fg=blue> Dev<fg=white;bg=black>pilot <fg=white;bg=black>Engine</>');
        $this->line("<fg=blue>------------------------------");
    }

    public function consoleFooter()
    {
        $this->line("------------------------------</>");
        $this->line('<fg=white;bg=black>Exiting<fg=blue> Dev<fg=white;bg=black>pilot <fg=white;bg=black>Engine</>');
        $this->line("------------------------------");
    }
}
