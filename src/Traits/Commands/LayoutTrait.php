<?php

namespace TechiesAfrica\Devpilot\Traits\Commands;

use Symfony\Component\Console\Helper\Table;
use TechiesAfrica\Devpilot\Exceptions\Deployments\DeploymentException;

trait LayoutTrait
{
    public function consoleHeader()
    {
        $this->line("------------------------------");
        $this->line('<fg=white;bg=black>Starting<fg=blue> Dev<fg=white;bg=black>pilot</>');
        $this->line("------------------------------");
    }

    public function consoleFooter()
    {
        $this->line("------------------------------");
        $this->line('<fg=white;bg=black>Exiting<fg=blue> Dev<fg=white;bg=black>pilot</>');
        $this->line("------------------------------");
    }
}